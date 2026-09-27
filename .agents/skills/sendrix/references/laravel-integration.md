# Sendrix Laravel Starter-Kit Integration Guide

This guide details the complete, production-ready implementation of Sendrix for this Laravel application. It enforces Hexagonal Architecture, strict types, Pest testing, and queue processing with Laravel Horizon.

---

## 1. Directory Structure

```
app/
├── Infrastructure/
│   ├── External/
│   │   └── Sendrix/
│   │       ├── SendrixClient.php              # Typed HTTP client communicating with Sendrix API
│   │       └── Exceptions/
│   │           ├── SendrixRateLimitException.php
│   │           └── SendrixApiException.php
│   ├── Mail/
│   │   └── Transports/
│   │       └── SendrixTransport.php           # Custom Symfony/Laravel Mail Transport
│   └── Messaging/
│       └── Jobs/
│           └── SendSendrixEmailJob.php        # Dedicated background job on 'emails' queue
├── Ports/
│   └── Out/
│       └── Messaging/
│           └── EmailSenderInterface.php       # Domain/App port interface (if not using Mailable)
config/
├── services.php                               # Sendrix credentials config
└── mail.php                                   # 'sendrix' mailer transport definition
tests/
└── Feature/
    └── Infrastructure/
        ├── SendrixClientTest.php              # Pest feature tests for HTTP client
        └── SendrixTransportTest.php           # Pest feature tests for mail transport
```

---

## 2. Configuration Setup

### `config/services.php`
```php
'sendrix' => [
    'base_url' => env('SENDRIX_BASE_URL', 'http://localhost:3000'),
    'project_id' => env('SENDRIX_PROJECT_ID'),
    'api_key' => env('SENDRIX_API_KEY'),
    'admin_secret' => env('SENDRIX_ADMIN_SECRET'),
],
```

### `config/mail.php`
```php
'mailers' => [
    'sendrix' => [
        'transport' => 'sendrix',
    ],
    // ...
],
```

### `.env` & `.env.example`
```dotenv
MAIL_MAILER=sendrix
SENDRIX_BASE_URL=https://sendrix.yourdomain.com
SENDRIX_PROJECT_ID=
SENDRIX_API_KEY=
SENDRIX_ADMIN_SECRET=
```

---

## 3. Implementation Classes

### SendrixClient (`app/Infrastructure/External/Sendrix/SendrixClient.php`)

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\External\Sendrix;

use App\Infrastructure\External\Sendrix\Exceptions\SendrixApiException;
use App\Infrastructure\External\Sendrix\Exceptions\SendrixRateLimitException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class SendrixClient
{
    private string $baseUrl;
    private string $projectId;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.sendrix.base_url'), '/');
        $this->projectId = (string) config('services.sendrix.project_id');
        $this->apiKey = (string) config('services.sendrix.api_key');

        if (empty($this->projectId) || empty($this->apiKey)) {
            throw new RuntimeException('Sendrix credentials (SENDRIX_PROJECT_ID and SENDRIX_API_KEY) must be configured.');
        }
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'X-Project-ID' => $this->projectId,
                'Accept' => 'application/json',
            ])
            ->timeout(10);
    }

    /**
     * Send email via Sendrix.
     *
     * @param array{
     *     to: string,
     *     subject: string,
     *     html: string,
     *     from_name?: string,
     *     reply_to?: string,
     *     cc?: array<string>,
     *     bcc?: array<string>
     * } $payload
     * @return array{
     *     success: bool,
     *     log_id: string,
     *     status: string,
     *     duplicated: bool
     * }
     */
    public function send(array $payload): array
    {
        $response = $this->client()->post('/api/v1/send', $payload);

        if ($response->status() === 429) {
            $retryAfter = (int) ($response->header('Retry-After') ?? $response->json('retry_after_seconds', 60));
            throw new SendrixRateLimitException("Sendrix rate limit exceeded. Retry after {$retryAfter}s.", $retryAfter);
        }

        if ($response->successful()) {
            $data = $response->json();

            return [
                'success' => true,
                'log_id' => (string) ($data['log_id'] ?? $data['id'] ?? ''),
                'status' => (string) ($data['status'] ?? ($data['duplicated'] ?? false ? 'duplicate' : 'sent')),
                'duplicated' => (bool) ($data['duplicated'] ?? false),
            ];
        }

        throw new SendrixApiException(
            "Sendrix API error [{$response->status()}]: {$response->body()}",
            $response->status()
        );
    }

    /**
     * Retrieve status of a sent email by log_id.
     *
     * @return array<string, mixed>
     */
    public function getStatus(string $logId): array
    {
        $response = $this->client()->get("/api/v1/send/{$logId}");

        if (! $response->successful()) {
            throw new SendrixApiException("Failed to fetch Sendrix log [{$logId}]: {$response->body()}", $response->status());
        }

        return (array) $response->json();
    }
}
```

### Exceptions (`app/Infrastructure/External/Sendrix/Exceptions/`)

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\External\Sendrix\Exceptions;

use RuntimeException;

final class SendrixRateLimitException extends RuntimeException
{
    public function __construct(string $message, public readonly int $retryAfterSeconds = 60)
    {
        parent::__construct($message, 429);
    }
}
```

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\External\Sendrix\Exceptions;

use RuntimeException;

final class SendrixApiException extends RuntimeException {}
```

---

## 4. Custom Mail Transport (`app/Infrastructure/Mail/Transports/SendrixTransport.php`)

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\Mail\Transports;

use App\Infrastructure\External\Sendrix\SendrixClient;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

final class SendrixTransport extends AbstractTransport
{
    public function __construct(private readonly SendrixClient $client)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $toAddresses = array_map(fn (Address $address) => $address->getAddress(), $email->getTo());
        $ccAddresses = array_map(fn (Address $address) => $address->getAddress(), $email->getCc());
        $bccAddresses = array_map(fn (Address $address) => $address->getAddress(), $email->getBcc());

        $payload = [
            'to' => $toAddresses[0] ?? '',
            'subject' => $email->getSubject() ?? '',
            'html' => $email->getHtmlBody() ?? (string) $email->getTextBody(),
        ];

        if (! empty($email->getFrom())) {
            $payload['from_name'] = $email->getFrom()[0]->getName();
        }

        if (! empty($email->getReplyTo())) {
            $payload['reply_to'] = $email->getReplyTo()[0]->getAddress();
        }

        if (! empty($ccAddresses)) {
            $payload['cc'] = $ccAddresses;
        }

        if (! empty($bccAddresses)) {
            $payload['bcc'] = $bccAddresses;
        }

        $this->client->send($payload);
    }

    public function __toString(): string
    {
        return 'sendrix';
    }
}
```

### Registration in `app/Providers/AppServiceProvider.php`
```php
use App\Infrastructure\External\Sendrix\SendrixClient;
use App\Infrastructure\Mail\Transports\SendrixTransport;
use Illuminate\Support\Facades\Mail;

public function boot(): void
{
    Mail::extend('sendrix', function () {
        return new SendrixTransport(app(SendrixClient::class));
    });
}
```

---

## 5. Horizon Background Job (`app/Infrastructure/Messaging/Jobs/SendSendrixEmailJob.php`)

When sending custom emails outside standard Mailables, use this dedicated queued job:

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Jobs;

use App\Infrastructure\External\Sendrix\Exceptions\SendrixRateLimitException;
use App\Infrastructure\External\Sendrix\SendrixClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class SendSendrixEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public int $backoff = 65;

    /**
     * @param array{
     *     to: string,
     *     subject: string,
     *     html: string,
     *     from_name?: string,
     *     reply_to?: string,
     *     cc?: array<string>,
     *     bcc?: array<string>
     * } $payload
     */
    public function __construct(public readonly array $payload)
    {
        // Enforce dispatch to the project's 'emails' queue monitored by Horizon
        $this->onQueue('emails');
    }

    public function handle(SendrixClient $client): void
    {
        try {
            $result = $client->send($this->payload);

            Log::info("Sendrix: Email sent to {$this->payload['to']}", [
                'log_id' => $result['log_id'],
                'duplicated' => $result['duplicated'],
            ]);
        } catch (SendrixRateLimitException $e) {
            $releaseSeconds = max(60, $e->retryAfterSeconds);
            Log::warning("Sendrix rate limit hit. Releasing job back to 'emails' queue for {$releaseSeconds}s.");
            $this->release($releaseSeconds);
        }
    }
}
```

---

## 6. Pest Feature Tests

### `tests/Feature/Infrastructure/SendrixClientTest.php`
```php
<?php

declare(strict_types=1);

use App\Infrastructure\External\Sendrix\Exceptions\SendrixRateLimitException;
use App\Infrastructure\External\Sendrix\SendrixClient;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'services.sendrix.base_url' => 'https://sendrix.test',
        'services.sendrix.project_id' => '00000000-0000-0000-0000-000000000000',
        'services.sendrix.api_key' => 'sk_proj_testkey123',
    ]);
});

it('sends email via sendrix and formats response correctly', function () {
    Http::fake([
        'https://sendrix.test/api/v1/send' => Http::response([
            'success' => true,
            'id' => 'resend-id-123',
            'log_id' => '01J3XYZ...',
            'timestamp' => '2026-07-25T10:06:00.000Z',
        ], 200),
    ]);

    $client = app(SendrixClient::class);
    $result = $client->send([
        'to' => 'test@example.com',
        'subject' => 'Hello',
        'html' => '<p>Hello</p>',
    ]);

    expect($result['success'])->toBeTrue()
        ->and($result['log_id'])->toBe('01J3XYZ...')
        ->and($result['duplicated'])->toBeFalse();

    Http::assertSent(function ($request) {
        return $request->header('Authorization')[0] === 'Bearer sk_proj_testkey123'
            && $request->header('X-Project-ID')[0] === '00000000-0000-0000-0000-000000000000'
            && $request['to'] === 'test@example.com';
    });
});

it('throws SendrixRateLimitException on 429 response', function () {
    Http::fake([
        'https://sendrix.test/api/v1/send' => Http::response([
            'error' => 'rate_limit_exceeded',
            'retry_after_seconds' => 45,
        ], 429, ['Retry-After' => '45']),
    ]);

    $client = app(SendrixClient::class);

    expect(fn () => $client->send([
        'to' => 'test@example.com',
        'subject' => 'Hello',
        'html' => '<p>Hello</p>',
    ]))->toThrow(SendrixRateLimitException::class);
});
```
