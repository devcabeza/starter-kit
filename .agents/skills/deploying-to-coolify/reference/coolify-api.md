# Coolify v4 REST API Reference

When programmatic access is available (via `COOLIFY_API_KEY` and `COOLIFY_URL`), agents can automate project provisioning and deployments using Coolify's REST API.

---

## Authentication & Headers

All requests require Bearer token authorization:

```bash
curl -s -H "Authorization: Bearer $COOLIFY_API_KEY" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json" \
     "$COOLIFY_URL/api/v1/projects"
```

---

## Key Endpoints

### 1. Servers & Projects

#### List Servers
```http
GET /api/v1/servers
```

#### List Projects
```http
GET /api/v1/projects
```

#### Create Project
```http
POST /api/v1/projects
```
Payload:
```json
{
  "name": "laravertex",
  "description": "Laravertex Laravel Application"
}
```
*(For staging, use `"name": "laravertex_staging"`)*

---

### 2. Applications Management

#### Create Application (Public / Git Repo)
```http
POST /api/v1/applications/public
```
Payload for **Production Web App**:
```json
{
  "project_uuid": "<project_uuid>",
  "server_uuid": "<server_uuid>",
  "environment_name": "production",
  "git_repository": "https://github.com/your-org/starter-kit",
  "git_branch": "main",
  "build_pack": "dockerfile",
  "dockerfile": "docker/prod/Dockerfile.prod",
  "base_directory": "/",
  "ports_exposes": "8000",
  "health_check_path": "/health"
}
```

Payload for **Production Worker (Horizon)**:
```json
{
  "project_uuid": "<project_uuid>",
  "server_uuid": "<server_uuid>",
  "environment_name": "production",
  "git_repository": "https://github.com/your-org/starter-kit",
  "git_branch": "main",
  "build_pack": "dockerfile",
  "dockerfile": "docker/prod/Dockerfile.prod",
  "base_directory": "/",
  "custom_docker_run_options": "--restart unless-stopped",
  "custom_labels": ""
}
```
*Note: In the application settings after creation, set the custom start command to `php artisan horizon` and remove any exposed public ports.*

Payload for **Staging Web App**:
```json
{
  "project_uuid": "<staging_project_uuid>",
  "server_uuid": "<server_uuid>",
  "environment_name": "staging",
  "git_repository": "https://github.com/your-org/starter-kit",
  "git_branch": "staging",
  "build_pack": "dockerfile",
  "dockerfile": "docker/staging/Dockerfile.staging",
  "base_directory": "/",
  "ports_exposes": "8000",
  "health_check_path": "/health"
}
```

---

### 3. Environment Variables

#### Set / Bulk Update Environment Variables
```http
POST /api/v1/applications/{uuid}/envs/bulk
```
Payload:
```json
{
  "data": [
    {"key": "APP_NAME", "value": "Laravertex", "is_literal": true},
    {"key": "APP_ENV", "value": "production", "is_literal": true},
    {"key": "APP_KEY", "value": "base64:...", "is_literal": true},
    {"key": "APP_DEBUG", "value": "false", "is_literal": true},
    {"key": "DB_CONNECTION", "value": "pgsql", "is_literal": true},
    {"key": "DB_HOST", "value": "postgresql", "is_literal": true},
    {"key": "DB_PORT", "value": "5432", "is_literal": true},
    {"key": "DB_DATABASE", "value": "laravertex_db", "is_literal": true},
    {"key": "QUEUE_CONNECTION", "value": "redis", "is_literal": true},
    {"key": "REDIS_HOST", "value": "redis", "is_literal": true}
  ]
}
```

---

### 4. Scheduled Tasks (Cronjobs)

#### Create Scheduled Task
```http
POST /api/v1/applications/{uuid}/tasks
```
Payload:
```json
{
  "name": "Laravel Scheduler",
  "command": "php artisan schedule:run",
  "frequency": "* * * * *"
}
```

---

### 5. Triggering & Monitoring Deployments

#### Trigger Deploy
```http
POST /api/v1/deploy?uuid={application_uuid}&force=false
```

Response returns deployment UUID:
```json
{
  "deployment_uuid": "abc-123-xyz"
}
```

#### Check Deployment Status & Logs
```http
GET /api/v1/deployments/{deployment_uuid}
```
Inspect `.status` (`in_progress`, `finished`, `failed`) and `.logs`.
