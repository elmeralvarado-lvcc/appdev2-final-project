<?php

namespace App\Http\Controllers;


use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use App\Actions\HandleCheckoutSessionCompleted;

class WebhookController extends CashierWebHookController
{
    public function handleCheckoutSessionCompleted($payload)
    {
        if ($payload['type'] === 'checkout.session.completed') {
            $sessionId = $payload['data']['object']['id'];
            (new HandleCheckoutSessionCompleted)->handle($sessionId);
        }
    }
}

