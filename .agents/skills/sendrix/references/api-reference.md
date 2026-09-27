# Sendrix API Reference

Complete specification of all Sendrix endpoints, authentication requirements, schemas, and status codes.

---

## Authentication Reference

| Context | Required Headers | Description |
|---|---|---|
| **Client Sending & Status** | `Authorization: Bearer sk_proj_...`<br>`X-Project-ID: <uuid>` | Project-scoped authentication. API keys are generated when creating a project. |
| **Admin Operations** | `Authorization: Bearer <SENDRIX_ADMIN_SECRET>` **OR**<br>`Authorization: Bearer <jwt_token>` | Server-to-server admin secret or JWT token returned by login/register. |
| **Queue Worker Cron** | `Authorization: Bearer <CRON_SECRET>` | Internal worker authentication for `/api/v1/cron/process-queue`. |
| **Resend Webhooks** | `Resend-Signature: <hmac_signature>` | Resend webhook signature verification. |

---

## 1. Client Sending Endpoints

### Send Transactional Email
`POST /api/v1/send`

Rate limited to **5 requests per minute** per project.

#### Headers
- `Authorization: Bearer sk_proj_...` (Required)
- `X-Project-ID: <uuid>` (Required)
- `Content-Type: application/json`

#### Request Body
```json
{
  "to": "customer@example.com",
  "subject": "Order Confirmation #1094",
  "html": "<h1>Thank you!</h1><p>Your order has been confirmed.</p>",
  "from_name": "Acme Store",
  "reply_to": "support@acme.com",
  "cc": ["finance@acme.com"],
  "bcc": ["archive@acme.com"]
}
```

#### Field Specifications & Constraints
- `to` (`string`, required): Single recipient valid email.
- `subject` (`string`, required): 1 to 998 characters.
- `html` (`string`, required): Minimum 1 character.
- `from_name` (`string`, optional): Custom display name (overrides project default).
- `reply_to` (`string`, optional): Valid reply-to email.
- `cc` (`string[]`, optional): Array of valid email addresses.
- `bcc` (`string[]`, optional): Array of valid email addresses.
- **Recipient Limit**: `to` + count(`cc`) + count(`bcc`) <= 50.

#### Responses

**200 OK — Immediate Delivery:**
```json
{
  "success": true,
  "id": "resend-uuid-1234",
  "log_id": "01J3XYZABCDEF0123456789",
  "timestamp": "2026-07-25T10:06:00.000Z"
}
```

**200 OK — Duplicate Detected (Identical `to + subject + html`):**
```json
{
  "success": true,
  "id": "01J3XYZABCDEF0123456789",
  "duplicated": true,
  "message": "Email already sent"
}
```

**202 Accepted — Queued for Async Delivery (Provider Timeout or Temporary Error):**
```json
{
  "log_id": "01J3XYZABCDEF0123456789",
  "status": "queued",
  "message": "Request queued for async delivery"
}
```

**429 Too Many Requests — Rate Limit Exceeded:**
```json
{
  "error": "rate_limit_exceeded",
  "message": "Rate limit exceeded. Try again in 45 seconds.",
  "retry_after_seconds": 45
}
```
*Headers: `X-RateLimit-Limit: 5`, `X-RateLimit-Remaining: 0`, `X-RateLimit-Reset: <timestamp>`, `Retry-After: 45`*

---

### Get Send Status
`GET /api/v1/send/:log_id`

#### Headers
- `Authorization: Bearer sk_proj_...`
- `X-Project-ID: <uuid>`

#### Response `200 OK`
```json
{
  "log_id": "01J3XYZABCDEF0123456789",
  "status": "delivered",
  "to": "customer@example.com",
  "subject": "Order Confirmation #1094",
  "duplicated": false,
  "created_at": "2026-07-25T10:06:00.000Z",
  "updated_at": "2026-07-25T10:06:05.000Z"
}
```

**Possible Status Values:**
- `queued`: Enqueued for async delivery attempt.
- `sent`: Successfully handed off to Resend.
- `delivered`: Confirmed delivery by destination mail server.
- `bounced`: Email bounced (hard or soft).
- `complained`: Recipient marked email as spam.
- `failed`: All retries exhausted or fatal provider failure.

---

## 2. Admin Authentication Endpoints

### Register Initial Admin Account
`POST /api/v1/auth/register`

Requires `SENDRIX_ADMIN_SECRET` Bearer token.

#### Request Body
```json
{
  "email": "admin@example.com",
  "password": "your-strong-password"
}
```

#### Response `201 Created`
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "email": "admin@example.com",
    "role": "admin",
    "created_at": "2026-07-25T10:00:00.000Z"
  }
}
```

### Admin Login
`POST /api/v1/auth/login`

#### Request Body
```json
{
  "email": "admin@example.com",
  "password": "your-strong-password"
}
```

#### Response `200 OK`
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "email": "admin@example.com",
    "role": "admin",
    "created_at": "2026-07-25T10:00:00.000Z"
  }
}
```

---

## 3. Admin Management Endpoints

All admin endpoints accept `Authorization: Bearer <SENDRIX_ADMIN_SECRET>` or `Authorization: Bearer <jwt_token>`.

### Create Project
`POST /api/v1/admin/projects`

#### Request Body
```json
{
  "name": "my-saas-app",
  "from_name": "My SaaS App",
  "from_email": "noreply@sendrix.com"
}
```

#### Response `201 Created`
```json
{
  "project_id": "f47ac10b-58cc-4372-a567-0e02b2c3d479",
  "name": "my-saas-app",
  "api_key": "sk_proj_a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6",
  "from_name": "My SaaS App",
  "from_email": "noreply@sendrix.com",
  "is_active": true,
  "created_at": "2026-07-25T10:05:00.000Z"
}
```
*Note: `api_key` is displayed only on creation.*

### List Projects
`GET /api/v1/admin/projects?page=1&limit=20`

#### Response `200 OK`
```json
{
  "projects": [
    {
      "project_id": "f47ac10b-58cc-4372-a567-0e02b2c3d479",
      "name": "my-saas-app",
      "from_name": "My SaaS App",
      "from_email": "noreply@sendrix.com",
      "is_active": true,
      "created_at": "2026-07-25T10:05:00.000Z"
    }
  ],
  "total": 1,
  "page": 1,
  "limit": 20
}
```

### Rotate Project API Key
`PATCH /api/v1/admin/projects/:id/rotate-key`

#### Response `200 OK`
```json
{
  "project_id": "f47ac10b-58cc-4372-a567-0e02b2c3d479",
  "api_key": "sk_proj_newkey99887766554433221100",
  "message": "Previous key has been invalidated"
}
```

### Retrieve Project Logs
`GET /api/v1/admin/projects/:id/logs?page=1&limit=50&status=delivered&since=2026-07-01T00:00:00Z`

#### Response `200 OK`
```json
{
  "logs": [
    {
      "id": "01J3XYZABCDEF0123456789",
      "to_recipient": "customer@example.com",
      "subject": "Order Confirmation #1094",
      "status": "delivered",
      "error_message": null,
      "created_at": "2026-07-25T10:06:00.000Z"
    }
  ],
  "total": 1,
  "page": 1,
  "limit": 50
}
```

### Global Metrics
`GET /api/v1/metrics?period=this_month`

Valid periods: `today`, `yesterday`, `this_week`, `last_week`, `this_month`, `last_month`, `this_year`.

#### Response `200 OK`
```json
{
  "global": {
    "total_sent": 1500,
    "total_delivered": 1475,
    "total_failed": 25,
    "total_bounced": 8,
    "total_complained": 1,
    "failure_rate": 1.67,
    "period": { "since": "2026-07-01T00:00:00.000Z", "until": "2026-07-31T23:59:59.999Z" }
  },
  "top_project": { "id": "f47ac10b...", "name": "my-saas-app", "total": 920 },
  "projects_breakdown": [
    { "project_id": "f47ac10b...", "name": "my-saas-app", "sent": 920, "delivered": 910 }
  ]
}
```

---

## 4. System & Webhook Endpoints

### Health Check
`GET /api/v1/health` (No authentication required)

#### Response `200 OK` (Healthy)
```json
{
  "status": "ok",
  "timestamp": "2026-07-25T10:00:00.000Z",
  "db_connected": true,
  "version": "1.0.0"
}
```

### Resend Webhook Receiver
`POST /api/v1/webhooks/resend`

- Header: `Resend-Signature: <hex_signature>`
- Handled events: `email.sent`, `email.delivered`, `email.bounced`, `email.complained`.
- Response: `200 OK` `{ "received": true, "matched_log": true }`

### Background Queue Worker (Cron)
`GET /api/v1/cron/process-queue`

- Header: `Authorization: Bearer <CRON_SECRET>`
- Response: `200 OK`
```json
{
  "processed": 3,
  "completed": 2,
  "failed": 1,
  "remaining": 0,
  "items": []
}
```
