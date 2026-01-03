<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');

        $event = Webhook::constructEvent(
            $payload,
            $sig,
            config('services.stripe.webhook_secret')
        );

        if ($event->type === 'payment_intent.succeeded') {

            $intent = $event->data->object;

            $payment = Payment::where('gateway_order_id', $intent->id)->first();

            if ($payment) {
                $payment->update([
                    'gateway_payment_id' => $intent->latest_charge,
                    'status' => 'success',
                    'meta' => $intent->toArray(),
                ]);

                $payment->order->update([
                    'status' => 'paid'
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}