<?php

namespace App\Services;

use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent($order)
    {
        // Implementation for creating a Stripe Payment Intent
        return PaymentIntent::create([
            'amount' => (int) ($order->total_amount * 100),
            'currency' => 'inr',
            'payment_method_types' => ['card'],
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ],
        ]);
    }

}