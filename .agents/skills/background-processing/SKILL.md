---
name: background-processing
description: "Apply this skill whenever a task involves heavy or time-consuming operations that could block the main thread. Use for: sending emails, generating reports/files, processing uploads, image manipulation, PDF generation, data exports, third-party API calls, webhooks, notifications, CSV/Excel exports, report rendering, file conversions, or any I/O-bound operation. Triggers when the user mentions generating, exporting, sending, processing, uploading, or any operation that could take more than 1-2 seconds. MANDATORY for all background-capable operations."
license: MIT
metadata:
  author: laravel
---

# Background Processing with Queues

Enforce background processing for all heavy operations to keep the application responsive. Use Laravel's queue system exclusively for tasks that would otherwise block the main thread.

## Core Principle

**Every time-consuming operation MUST run in the background via queues.** This is not optional. The only exception is when the user explicitly asks for synchronous execution and confirms the trade-off.

## When to Queue (Always)

- **Email sending**: Welcome emails, password resets, notifications, newsletters
- **File generation**: PDFs, reports, CSVs, Excel files, exports, invoices
- **File processing**: Image resizing, video encoding, document conversions
- **Third-party calls**: External APIs, webhooks, payment gateways, service integrations
- **Data operations**: Bulk imports, data sync, analytics calculations, aggregations
- **Notifications**: Push notifications, SMS, Slack/Discord messages
- **Heavy computations**: Report generation, data analysis, complex calculations

## Implementation Pattern

### 1. Create a Job Class

```bash
php artisan make:job SendWelcomeEmailJob
```

### 2. Job Structure

```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    public function __construct(
        private readonly User $user,
    ) {}

    public function handle(): void
    {
        // Job logic here
    }

    public function failed(\Throwable $exception): void
    {
        // Handle failure (log, notify, etc.)
    }
}
```

### 3. Dispatching Jobs

```php
// Fire and forget (most common)
SendWelcomeEmailJob::dispatch($user);

// Delayed execution
SendWelcomeEmailJob::dispatch($user)->delay(now()->addMinutes(5));

// On specific queue
SendWelcomeEmailJob::dispatch($user)->onQueue('emails');

// With chain (sequential jobs)
dispatch(new GenerateReportJob($user))
    ->chain([
        new SendReportEmailJob($user, $report),
    ]);
```

## Queue Configuration

### Horizon Setup (Already Installed)

This project uses Laravel Horizon for queue management. Key queues to define:

```php
// config/horizon.php - queues array
'queues' => [
    'default',
    'emails',
    'reports',
    'heavy',
    'imports',
],
```

### Job Properties

| Property | When to Use | Value |
|----------|-------------|-------|
| `$tries` | Critical jobs that must succeed | 3-5 |
| `$backoff` | Jobs hitting rate limits | 60-300 seconds |
| `$timeout` | Long-running jobs | 60-300 seconds |
| `$maxExceptions` | Jobs that may hit transient errors | 3 |
| `$afterCommit` | Jobs that depend on DB state | true |

## Real-World Examples

### Email Notifications

```php
class SendOrderConfirmationJob implements ShouldQueue
{
    public int $tries = 3;
    public int $timeout = 30;

    public function handle(): void
    {
        Mail::to($this->order->user)
            ->send(new OrderConfirmationMail($this->order));
    }
}
```

### Report Generation

```php
class GenerateSalesReportJob implements ShouldQueue
{
    public int $timeout = 300; // 5 minutes
    public int $tries = 1;

    public function __construct(
        private readonly Carbon $startDate,
        private readonly Carbon $endDate,
        private readonly User $user,
    ) {}

    public function handle(): void
    {
        $data = Sale::whereBetween('created_at', [...])->get();
        $pdf = Pdf::loadView('reports.sales', compact('data'));
        
        Storage::disk('local')->put(
            "reports/sales-{$this->user->id}.pdf",
            $pdf->output()
        );

        Mail::to($this->user)->send(new ReportReadyMail(...));
    }
}
```

### File Processing

```php
class ProcessUploadedCsvJob implements ShouldQueue
{
    public int $timeout = 600;
    public int $tries = 1;

    public function handle(): void
    {
        $rows = $this->readCsv($this->file);
        $imported = $this->processRows($rows);
        
        Notification::send($this->user, new CsvImportCompleteNotification(
            imported: $imported,
            file: $this->file,
        ));
    }
}
```

## Error Handling

### Failed Jobs

Always implement `failed()` method for critical jobs:

```php
public function failed(\Throwable $exception): void
{
    Log::error('Job failed', [
        'job' => static::class,
        'user' => $this->user->id,
        'error' => $exception->getMessage(),
    ]);

    Notification::send($this->user, new JobFailedNotification(
        job: static::class,
        error: $exception->getMessage(),
    ));
}
```

### Retry Logic

```php
public function retryUntil(): Carbon
{
    return now()->addHours(24); // Retry for 24 hours
}

public function backoff(): array
{
    return [30, 60, 120]; // Progressive backoff
}
```

## Testing Jobs

```php
it('sends welcome email via queue', function ()
{
    Queue::fake();

    SendWelcomeEmailJob::dispatch($user);

    Queue::assertPushed(SendWelcomeEmailJob::class);
});

it('processes job correctly', function ()
{
    $job = new SendWelcomeEmailJob($user);
    
    $job->handle();
    
    Mail::assertSent(OrderConfirmationMail::class);
});
```

## Verification Checklist

Before marking any background task as complete:

1. ✅ Job class created with `implements ShouldQueue`
2. ✅ Dispatched via `::dispatch()` not synchronous call
3. ✅ Proper timeout set for long-running operations
4. ✅ Error handling implemented in `failed()` method
5. ✅ Queue name specified for appropriate worker
6. ✅ Job tested with `Queue::fake()` or actually dispatched

## Decision Rules

- **Default to async**: When in doubt, queue it. It's easier to make async sync than the reverse.
- **Never queue auth**: Authentication must be synchronous for security feedback.
- **Log everything**: Queue failures are silent. Always log context.
- **Test both paths**: Test job dispatch AND job execution separately.
- **Monitor with Horizon**: Use Horizon dashboard to track queue health.
