<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Checkout\Session;
use Stripe\Customer;
// use Stripe\PaymentIntent;
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

    public function createCheckoutSession(Order $order)
    {
        // Implementation for creating a Stripe Payment Intent
        // return PaymentIntent::create([
        //     'amount' => $order->total_amount * 100,
        //     'currency' => 'inr',
        //     'automatic_payment_methods' => ['enabled' => true],
        //     'metadata' => [
        //         'order_id' => $order->id,
        //         'order_number' => $order->order_number,
        //     ],
        // ]);

        // return Session::create([
        //     'mode' => 'payment',
        //     'payment_method_types' => ['card'],
        //     'line_items' => [[
        //         'price_data' => [
        //             'currency' => 'usd',
        //             'product_data' => [
        //                 'name' => 'Order #' . $order->order_number,
        //             ],
        //             'unit_amount' => $order->total_amount * 100,
        //         ],
        //         'quantity' => 1,
        //     ]],
        //     'customer_email' => $order->email,
        //     'metadata' => [
        //         'order_id' => $order->id,
        //     ],
        //     'success_url' => route('checkout.stripe.processing', ['locale' => app()->getLocale(), 'uuid' => $order->uuid]),
        //     'cancel_url' => route('checkout.failure', ['locale' => app()->getLocale(), 'uuid' => $order->uuid]),
        // ]);

        // Create Stripe Customer (this is how you pass customer name)
        $customer = Customer::create([
            'name'  => $order->first_name . ' ' . $order->last_name,
            'email' => $order->email,
        ]);

        return Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'customer' => $customer->id,
            'client_reference_id' => $order->uuid,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => 'Order #' . $order->order_number,
                    ],
                    'unit_amount' => (int) round($order->total_amount * 100),
                ],
                'quantity' => 1,
            ]],
            // 'customer_email' => $order->email,
            'metadata' => [
                'order_id' => $order->id,
            ],
            'success_url' => route('checkout.stripe.processing', [
                'locale' => app()->getLocale(),
                'uuid' => $order->uuid
            ]),
            'cancel_url' => route('checkout.failure', [
                'locale' => app()->getLocale(),
                'uuid' => $order->uuid
            ]),
        ]);


    }

}