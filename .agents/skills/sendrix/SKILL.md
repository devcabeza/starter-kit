---
name: sendrix
description: "Sendrix transactional email gateway integration, configuration, and operations for this Laravel starter-kit. Use this skill whenever the user mentions Sendrix, transactional emails, email sending, configuring Sendrix credentials, integrating Sendrix with Laravel Mail or Horizon queues, managing rate limits (5 req/min) on the 'emails' queue, testing emails with Pest, or troubleshooting Sendrix delivery status."
license: MIT
metadata:
  author: starter-kit
---

# Sendrix Email Proxy — Laravel Starter-Kit Integration

Sendrix is the centralized transactional email proxy for this application. Rather than connecting directly to Resend, this Laravel application dispatches all transactional emails through Sendrix using a dedicated API key (`SENDRIX_KEY`) via Bearer token authentication.

---

## Starter-Kit Architectural Alignment

All Sendrix integrations in this application MUST follow our core architectural standards:

### 1. Hexagonal Architecture (Ports & Adapters)
- **Domain & Application layers never depend on Sendrix**: `app/Domain/` and `app/Application/` contain pure business logic and use cases (e.g. `SendMagicLinkAction`). They only interact with outbound interfaces in `App\Ports\Out\Messaging\*`.
- **Infrastructure Layer Adapters**:
  - **Option A (Recommended — Zero-Change Mailable Transport)**: A custom Laravel mail transport `App\Infrastructure\Mail\Transports\SendrixTransport` registered as `MAIL_MAILER=sendrix`. Existing mailables (such as `MagicLinkMail`) and `MagicLinkNotifier` automatically deliver through Sendrix without modifying any business logic.
  - **Option B (Direct Outbound Adapter)**: Implementing an outbound port `App\Ports\Out\Messaging\EmailSenderInterface` in `App\Infrastructure\Messaging\SendrixEmailSender`.

### 2. Horizon & Background Processing (`emails` queue)
- **Never call Sendrix synchronously** in HTTP controllers, Livewire components, or CLI requests.
- **Dedicated Queue**: All email dispatches MUST be sent to the **`emails`** queue (`$this->onQueue('emails')`), which is actively monitored and scaled by Laravel Horizon (`config/horizon.php`).
- **Rate Limit Resilience (5 req/min)**: Sendrix limits each project to 5 requests per minute. When HTTP `429 Too Many Requests` is received, the queued job must release itself back to Horizon using `$this->release($retryAfterSeconds)` with an exponential backoff exceeding 60 seconds.

### 3. Progressive Disclosure References
Before implementing or modifying Sendrix code, read the targeted reference:
- `references/laravel-integration.md` — Full starter-kit code: `SendrixService`, `SendrixTransport`, `SendSendrixEmailJob`, and Pest test suite.
- `references/api-reference.md` — Full Sendrix API specifications, schemas, error codes, and curl examples.
- `references/deployment.md` — Deploying Sendrix instance on Coolify / Docker alongside this Laravel application.

---

## Starter-Kit Configuration

### 1. Environment Variables (`.env` and `.env.example`)
```dotenv
# Email Gateway (Sendrix)
MAIL_MAILER=sendrix
SENDRIX_BASE_URL=https://sendrix.alejandrocabeza.dev
SENDRIX_KEY=sk_proj_a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6
```

### 2. Service Configuration (`config/services.php`)
```php
'sendrix' => [
    'key' => env('SENDRIX_KEY', env('SENDRIX_API_KEY')),
    'api_key' => env('SENDRIX_KEY', env('SENDRIX_API_KEY')),
    'base_url' => env('SENDRIX_BASE_URL', 'https://sendrix.alejandrocabeza.dev'),
],
```

### 3. Mailer Configuration (`config/mail.php`)
```php
'mailers' => [
    'sendrix' => [
        'transport' => 'sendrix',
    ],
    // ...
],
```

### 4. Transport Registration (`app/Providers/AppServiceProvider.php`)
```php
use App\Mail\SendrixTransport;
use Illuminate\Support\Facades\Mail;

protected function configureMailTransport(): void
{
    Mail::extend('sendrix', function () {
        return new SendrixTransport(
            key: (string) config('services.sendrix.key', config('services.sendrix.api_key', '')),
            baseUrl: (string) config('services.sendrix.base_url', 'https://sendrix.alejandrocabeza.dev'),
        );
    });
}
```

---

## Core Protocol & Response Handling

When communicating with Sendrix (`POST /api/v1/send`):

| HTTP Status | Sendrix Meaning | Laravel Application Action |
|---|---|---|
| **`200 OK`** | Delivered immediately to Resend, OR duplicate detected (`duplicated: true`). | Mark as sent / log success. Do not retry duplicates. |
| **`202 Accepted`** | Queued in Sendrix async retry queue (temporary Resend timeout/error). | Mark as enqueued in Sendrix. No immediate action needed. |
| **`429 Rate Limit`** | Project exceeded 5 requests/minute. | Read `Retry-After` header or `retry_after_seconds`. Call `$this->release($retryAfter)`. |
| **`401 / 403`** | Missing headers, invalid `sk_proj_` key, or project inactive. | Fail job permanently, log error to Telescope/Pail, alert developer. |
| **`422 Unprocessable`** | Validation failure (invalid email format, subject > 998 chars, > 50 recipients). | Fail job permanently; inspect payload. |

---

## Pest Testing Pattern for Sendrix

This repository uses **Pest** (`tests/Feature/...`). Always test Sendrix interactions using `Http::fake()` to avoid external calls:

```php
use App\Mail\SendrixTransport;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mime\Email;

it('dispatches email to sendrix with required headers and payload', function () {
    Http::fake([
        '*/api/v1/send' => Http::response([
            'success' => true,
            'id' => 'resend-123',
            'log_id' => '01J3XYZ...',
            'timestamp' => now()->toISOString(),
        ], 200),
    ]);

    $transport = new SendrixTransport(
        key: 'sk_proj_test_key',
        baseUrl: 'https://sendrix.alejandrocabeza.dev',
    );

    $email = (new Email)
        ->to('user@example.com')
        ->subject('Test Subject')
        ->html('<p>Hello</p>');

    $sentMessage = $transport->send($email);

    expect($sentMessage)->not->toBeNull()
        ->and($sentMessage->getMessageId())->toBe('resend-123');

    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization', 'Bearer sk_proj_test_key')
            && ! $request->hasHeader('X-Project-ID')
            && $request['to'] === 'user@example.com';
    });
});
```

---

## Code Quality Standards
- All PHP classes MUST declare `declare(strict_types=1);`.
- Use PHP 8.4/8.3 constructor property promotion: `public function __construct(private readonly SendrixClient $client) {}`.
- Run `vendor/bin/pint --format agent` on modified PHP files before completing any task.
