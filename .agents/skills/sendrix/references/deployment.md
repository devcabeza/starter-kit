# Sendrix Deployment & Infrastructure Guide

This guide details deploying and operating a self-hosted Sendrix instance alongside this Laravel starter-kit, with focus on Coolify, Docker, and Vercel.

---

## 1. Coolify Deployment (Recommended for this Starter-Kit)

As detailed in `deploying-to-coolify`, this starter-kit runs on Coolify with isolated Production and Staging environments. Sendrix can be deployed as an independent companion service in the same Coolify project.

### Architecture in Coolify
```
[ Coolify Project ]
   │
   ├──► Laravel Web Service (Port 8000, Traefik HTTPS)
   │      • Sends mail to Sendrix via internal or public URL
   │
   ├──► Laravel Horizon Worker (emails queue)
   │      • Processes mail jobs, dispatches to Sendrix HTTP API
   │
   └──► Sendrix Service (Port 3000, Traefik HTTPS or Internal Docker Network)
          • Multi-stage build via Dockerfile.prod
          • Volume: sendrix-data -> /data (SQLite persistence)
          • Coolify Scheduled Task: Cron worker every 1 minute
```

### Coolify Service Setup
1. **Repository & Build**:
   - Point to the Sendrix repository.
   - Build Pack: `Dockerfile`.
   - Dockerfile Path: `Dockerfile.prod`.
2. **Ports & Routing**:
   - Port Expose: `3000`.
   - Domain: `https://sendrix.yourdomain.com` (or internal container name for private routing).
3. **Persistent Volume (Mandatory for SQLite)**:
   - Volume Name: `sendrix-data`.
   - Destination Path: `/data`.
   - Set environment variable: `TURSO_DATABASE_URL=file:/data/sendrix.db`.
4. **Coolify Scheduled Task (Cron Worker)**:
   - In Coolify, go to the **Scheduled Tasks** tab of the Sendrix service.
   - Command:
     ```bash
     curl -s -f -H "Authorization: Bearer ${CRON_SECRET}" http://127.0.0.1:3000/api/v1/cron/process-queue
     ```
   - Frequency: `* * * * *` (Every 1 minute).
   - This task retries queued emails with exponential backoff (0s → 60s → 300s → 900s).

---

## 2. Environment Variables Reference

| Variable | Required | Value / Example | Notes |
|---|---|---|---|
| `RESEND_API_KEY` | **Yes** | `re_xxxxxxxxxxxxxxxxxxxxxx` | Resend API key for outbound delivery. |
| `TURSO_DATABASE_URL` | **Yes** | `file:/data/sendrix.db` | Local SQLite path or remote `libsql://...`. |
| `TURSO_AUTH_TOKEN` | No* | — | Required only if connecting to remote Turso. Leave blank for SQLite. |
| `SENDRIX_ADMIN_SECRET` | **Yes** | `<32-byte-hex>` | Bootstrap admin registration and server-to-server admin calls. |
| `SENDRIX_HASH_SECRET` | **Yes** | `<32-byte-hex>` | Pepper appended to project API keys before bcrypt hashing. |
| `JWT_SECRET` | **Yes** | `<32-byte-hex>` | Secret used to sign admin session JWTs. |
| `CRON_SECRET` | **Yes** | `<32-byte-hex>` | Secret protecting `/api/v1/cron/process-queue`. |
| `RESEND_WEBHOOK_SECRET` | No | `whsec_xxxxxxxxxxxxxxxxx` | HMAC-SHA256 signature secret for Resend webhooks. |
| `FROM_EMAIL_DEFAULT` | No | `no-reply@yourdomain.com` | Default fallback sender address. |
| `PORT` | No | `3000` | Port served by Node.js. |

### Generating Secrets
Generate 32-byte hex strings via terminal:
```bash
openssl rand -hex 32
```

---

## 3. Resend Webhook Configuration

To receive delivery status updates (`email.sent`, `email.delivered`, `email.bounced`, `email.complained`):

1. Open the [Resend Webhooks Dashboard](https://resend.com/webhooks).
2. Click **Add Webhook**.
3. Endpoint URL:
   ```
   https://sendrix.yourdomain.com/api/v1/webhooks/resend
   ```
4. Select events:
   - `email.sent`
   - `email.delivered`
   - `email.bounced`
   - `email.complained`
5. Copy the signing secret (`whsec_...`) and configure `RESEND_WEBHOOK_SECRET` in Sendrix.
