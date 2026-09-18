---
name: deploying-to-coolify
description: "Deploys and manages Laravel applications on Coolify across Production and Staging environments. Use when the user wants to deploy to Coolify, ship to Coolify, configure Coolify services, set up production or staging environments, configure Horizon queue workers, configure Coolify native scheduled tasks (cronjobs), update deployments, manage environment variables, or troubleshoot Coolify deployments. Enforces docker/prod/Dockerfile.prod on main branch for Production, and docker/staging/Dockerfile.staging on staging branch for Staging."
license: MIT
metadata:
  author: starter-kit
---

# Deploying with Coolify

This guide defines the architecture, configuration standards, and deployment procedures for running this Laravel application on Coolify. It covers both **Production** and **Staging** environments.

---

## Environment Matrix

Always verify the target environment before creating resources or deploying. The two environments are strictly isolated:

| Specification | Production Environment | Staging Environment |
|---|---|---|
| **Naming Convention** | `{project}` (e.g. `laravertex`) | `{project}_staging` (e.g. `laravertex_staging`) |
| **Git Branch** | **`main`** *(Strict: never deploy production from any other branch)* | **`staging`** *(Strict: pre-production testing branch)* |
| **Dockerfile Path** | `docker/prod/Dockerfile.prod` | `docker/staging/Dockerfile.staging` |
| **Base Directory** | `/` | `/` |
| **Build Pack** | `Dockerfile` | `Dockerfile` |
| **`APP_ENV`** | `production` | `staging` |
| **`APP_DEBUG`** | `false` | `false` (or `true` if debugging staging) |
| **Database Name** | `{project}_db` | `{project}_staging_db` |
| **Web Service Port** | `8000` (Internal port mapped by Traefik) | `8000` (Internal port mapped by Traefik) |
| **Health Check Path** | `/health` | `/health` |
| **Queue Worker** | Dedicated container: `php artisan horizon` | Dedicated container: `php artisan horizon` |
| **Cronjobs / Scheduler** | Coolify Scheduled Tasks: `* * * * * php artisan schedule:run` | Coolify Scheduled Tasks: `* * * * * php artisan schedule:run` |

---

## Multi-Service Architecture per Environment

Each environment (Production or Staging) consists of three interconnected components:

```
[ Coolify Project / Environment ]
       │
       ├──► 1. Web Application Service (Public HTTP)
       │      • Dockerfile: docker/prod/Dockerfile.prod (or staging)
       │      • Exposed port: 8000 (Routed by Traefik with SSL)
       │      • Health check: /health
       │      • Entrypoint runs migrations & optimizes caches on boot
       │      │
       │      └──► Coolify Scheduled Tasks (Cronjobs)
       │             • Feature: Native Coolify Scheduled Tasks tab
       │             • Command: php artisan schedule:run
       │             • Frequency: * * * * * (every minute)
       │             • Runs via `docker exec` in Web container (0 MB extra RAM)
       │
       └──► 2. Queue Worker Service (Background)
              • Dockerfile: Same Dockerfile as Web
              • Public domains/ports: NONE (Private service)
              • Custom Start Command: php artisan horizon
              • Shares same Database & Redis
```

---

## 1. Web Application Service Configuration

### Build & Container Settings
- **Source**: Connected Git repository.
- **Branch**:
  - Production: `main`
  - Staging: `staging`
- **Build Pack**: `Dockerfile`
- **Dockerfile Path**:
  - Production: `docker/prod/Dockerfile.prod`
  - Staging: `docker/staging/Dockerfile.staging`
- **Base Directory**: `/`
- **Ports Exposes**: `8000`
- **Healthcheck Path**: `/health`
- **Auto-deploy**: Enable push-to-deploy for automatic deployments when commits land on the respective branch.

### Startup Lifecycle
The container entrypoint script (`entrypoint-prod.sh` or `entrypoint-staging.sh`) automatically:
1. Validates and enforces `https://` on `APP_URL` behind the reverse proxy.
2. Runs database migrations safely: `php artisan migrate --force`.
3. Compiles production caches: `config:cache`, `route:cache`, `view:cache`, `event:cache`.
4. Creates public storage link: `php artisan storage:link --force`.
5. Starts PHP 8.4-FPM in background (`php-fpm -D`).
6. Starts Nginx in foreground to serve incoming HTTP traffic.

---

## 2. Queue Worker Service (Horizon)

Background jobs must **never** run in the web container. A separate worker service ensures background processing never starves HTTP requests.

### Configuration
- **Source**: Same Git repository and branch (`main` for prod, `staging` for staging).
- **Build Pack**: `Dockerfile` pointing to the same Dockerfile (`docker/prod/Dockerfile.prod` or `docker/staging/Dockerfile.staging`).
- **Domains & Ports**: **Do not assign any domain or expose any public ports**.
- **Custom Docker Run / Start Command**:
  ```bash
  php artisan horizon
  ```
- **Why this works**:
  The entrypoint script inspects `$@`. When a custom command is detected, it skips Nginx and PHP-FPM and executes `exec "$@"`. This runs Horizon as PID 1, allowing proper propagation of `SIGTERM` signals for graceful worker termination during redeployments.
- **Restart Policy**: `always` or `unless-stopped`.

---

## 3. Cronjobs & Scheduled Tasks

Laravel's scheduler runs every minute via `php artisan schedule:run`. In Coolify, configure this using the native **Scheduled Tasks** feature:

### Setup in Coolify Dashboard
1. Navigate to the **Web Application** service in Coolify.
2. Open the **Scheduled Tasks** tab.
3. Click **Add Scheduled Task**:
   - **Name**: `Laravel Scheduler`
   - **Command**: `php artisan schedule:run`
   - **Frequency / Cron Expression**: `* * * * *`
4. Click **Save**.

### Why Native Scheduled Tasks?
- Coolify executes the task inside the running web container via `docker exec`.
- Eliminates the need for a separate scheduler container, saving memory and CPU.
- Uses the already-booted PHP environment and database connections.

---

## 4. Environment Variables Checklist

Set these variables under the **Environment Variables** tab of both Web and Worker services (they must share the same DB and Redis):

### Core Laravel Settings
```ini
APP_NAME=Laravertex
APP_ENV=production          # Use 'staging' for staging
APP_KEY=base64:...          # Generate with php artisan key:generate
APP_DEBUG=false             # false for prod and staging
APP_URL=https://your-domain.com
LOG_CHANNEL=stack
LOG_LEVEL=info
```

### PostgreSQL Database
If using Coolify's managed PostgreSQL database, use internal Docker network names or service UUIDs:
```ini
DB_CONNECTION=pgsql
DB_HOST=postgresql-service-name   # Or Coolify internal IP / hostname
DB_PORT=5432
DB_DATABASE=laravertex_db         # Use laravertex_staging_db for staging
DB_USERNAME=root
DB_PASSWORD=your_secure_password
```

### Redis / Valkey (Queues & Cache)
Horizon requires Redis:
```ini
REDIS_CLIENT=phpredis
REDIS_HOST=redis-service-name     # Coolify internal Redis hostname
REDIS_PORT=6379
REDIS_PASSWORD=your_redis_password
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis
```

---

## 5. Deployment Workflow & Sequence

When deploying a new project or environment on Coolify, follow this exact sequence to prevent race conditions or startup crashes:

```
1. Provision Database (PostgreSQL) & Redis
   └─ Ensure both services are in "Running" state

2. Deploy Web Application
   ├─ Configures APP_KEY, DB_*, REDIS_*
   ├─ Runs migrations (php artisan migrate --force)
   └─ Confirms healthcheck passes at /health

3. Configure Native Scheduled Tasks
   └─ Add * * * * * php artisan schedule:run to the Web container

4. Deploy Queue Worker (Horizon)
   ├─ Same repository, branch, and Dockerfile
   ├─ Custom start command: php artisan horizon
   └─ Shares DB_* and REDIS_* variables
```

---

## 6. Updating & Redeploying

### Code Updates
- Pushing to `main` triggers auto-deploy for Production (Web and Worker).
- Pushing to `staging` triggers auto-deploy for Staging (Web and Worker).

### Graceful Queue Restarts
When redeploying the Worker container:
- Coolify sends `SIGTERM` to the container.
- Horizon intercepts `SIGTERM` and initiates a graceful pause, allowing active jobs to finish up to `timeout` seconds before terminating.
- The new container boots and resumes queue processing without dropping jobs.

---

## Reference Guides

- [Coolify API Automation](reference/coolify-api.md): REST API v4 endpoints to create, inspect, and deploy applications programmatically.
- [Deployment Checklists](reference/checklists.md): Step-by-step checklists for initial rollout, updates, and maintenance.
