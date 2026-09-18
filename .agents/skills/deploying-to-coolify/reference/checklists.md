# Coolify Deployment Checklists

Use these checklists when provisioning, updating, or debugging Laravel environments on Coolify.

---

## 1. Initial Production Environment Setup

Copy and track progress during initial rollout:

```markdown
- [ ] 1. Target server identified in Coolify
- [ ] 2. PostgreSQL database created (`{project}_db`)
- [ ] 3. Redis / Valkey service created (`{project}_redis`)
- [ ] 4. Production Web App created:
  - [ ] Branch: `main`
  - [ ] Build pack: `Dockerfile`
  - [ ] Dockerfile path: `docker/prod/Dockerfile.prod`
  - [ ] Base directory: `/`
  - [ ] Port: `8000`
  - [ ] Healthcheck: `/health`
- [ ] 5. Production Environment Variables set:
  - [ ] `APP_KEY` generated & set
  - [ ] `APP_ENV=production` & `APP_DEBUG=false`
  - [ ] `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
  - [ ] `REDIS_HOST`, `REDIS_PORT`, `QUEUE_CONNECTION=redis`
  - [ ] `APP_URL` (pointing to production domain with https://)
- [ ] 6. Deploy Production Web App & verify `/health` returns 200
- [ ] 7. Configure Native Scheduled Task on Web App:
  - [ ] Command: `php artisan schedule:run`
  - [ ] Frequency: `* * * * *`
- [ ] 8. Production Worker created:
  - [ ] Branch: `main`
  - [ ] Dockerfile path: `docker/prod/Dockerfile.prod`
  - [ ] Role activation: Add `CONTAINER_ROLE=worker` to Environment Variables
  - [ ] NO public domain / ports assigned
  - [ ] Identical DB and Redis environment variables copied
- [ ] 9. Deploy Worker & verify Horizon dashboard or logs
```

---

## 2. Initial Staging Environment Setup

```markdown
- [ ] 1. Project / Environment named with suffix: `{project}_staging`
- [ ] 2. PostgreSQL database created (`{project}_staging_db`)
- [ ] 3. Redis service created or isolated DB assigned
- [ ] 4. Staging Web App created:
  - [ ] Branch: `staging`
  - [ ] Build pack: `Dockerfile`
  - [ ] Dockerfile path: `docker/staging/Dockerfile.staging`
  - [ ] Base directory: `/`
  - [ ] Port: `8000`
  - [ ] Healthcheck: `/health`
- [ ] 5. Staging Environment Variables set:
  - [ ] `APP_KEY` set
  - [ ] `APP_ENV=staging` & `APP_DEBUG=false`
  - [ ] `DB_DATABASE={project}_staging_db`
  - [ ] `REDIS_HOST`, `QUEUE_CONNECTION=redis`
  - [ ] `APP_URL` (staging preview domain)
- [ ] 6. Deploy Staging Web App & verify migrations completed
- [ ] 7. Configure Native Scheduled Task on Staging Web App:
  - [ ] Command: `php artisan schedule:run`
  - [ ] Frequency: `* * * * *`
- [ ] 8. Staging Worker created:
  - [ ] Branch: `staging`
  - [ ] Dockerfile path: `docker/staging/Dockerfile.staging`
  - [ ] Role activation: Add `CONTAINER_ROLE=worker` to Environment Variables
  - [ ] NO public domain / ports assigned
- [ ] 9. Deploy Worker & test background jobs
```

---

## 3. Routine Deployment / Code Update

```markdown
- [ ] 1. Ensure all code changes are committed and pushed:
  - [ ] For Staging: push to `staging`
  - [ ] For Production: pull request merged to `main`
- [ ] 2. If automatic push-to-deploy is enabled:
  - [ ] Monitor Web App deployment log in Coolify
  - [ ] Verify database migrations ran cleanly
- [ ] 3. Verify Worker deployment:
  - [ ] Confirm Horizon received SIGTERM and restarted gracefully
  - [ ] Check active workers count in Horizon dashboard
- [ ] 4. Confirm `/health` HTTP response on live domain
```

---

## 4. Diagnostics & Troubleshooting

### Horizon Worker Crashing or Exiting
- Check Worker logs in Coolify.
- Verify `REDIS_HOST` and credentials: can the worker container ping the Redis service on the Docker network?
- Verify that `php artisan horizon` was set as the custom start command.

### Scheduled Tasks Not Running
- Navigate to Coolify -> Web Application -> **Scheduled Tasks**.
- Check execution logs for `php artisan schedule:run`.
- Verify the Web container is in "Running" status.

### 502 Bad Gateway on Web App
- Check if container passed the healthcheck at `http://localhost:8000/health`.
- Check if migrations failed during entrypoint boot.
- Verify `APP_KEY` is properly populated.
