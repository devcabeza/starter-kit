<?php

declare(strict_types=1);

namespace App\Listeners;

use Danestves\LaravelPolar\Events\WebhookHandled;
use Illuminate\Support\Facades\Log;

class PolarEventListener
{
    /**
     * Handle received Polar webhooks.
     */
    public function handle(WebhookHandled $event): void
    {
        $type = $event->payload['type'] ?? 'unknown';

        Log::info("Polar webhook received: {$type}", [
            'type' => $type,
            'payload' => $event->payload,
        ]);

        // Process webhook event according to business requirements:
        // match ($type) {
        //     'order.created' => ...,
        //     'subscription.created' => ...,
        //     'subscription.updated' => ...,
        //     'subscription.active' => ...,
        //     'subscription.canceled' => ...,
        //     'subscription.revoked' => ...,
        //     default => null,
        // };
    }
}
