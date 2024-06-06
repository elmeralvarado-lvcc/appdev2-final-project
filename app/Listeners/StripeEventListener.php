<?php

namespace App\Listeners;

use App\Actions\HandleCheckoutSessionCompleted;
use Laravel\Cashier\Events\WebhookReceived;
use Log;

class StripeEventListener
{
    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        Log::info('WebhookReceived');
        if ($event->payload['type'] === 'checkout.session.completed') {
            $sessionId = $event->payload['data']['object']['id'];
            (new HandleCheckoutSessionCompleted)->handle($sessionId);
        }
    }
}
