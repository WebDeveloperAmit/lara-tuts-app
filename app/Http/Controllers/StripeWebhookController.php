<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle_old(Request $request)
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

    public function handle_old_working(Request $request)
    {
        $event = Webhook::constructEvent(
            $request->getContent(),
            $request->header('Stripe-Signature'),
            config('services.stripe.webhook_secret')
        );

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;

            $payment = Payment::where('gateway_order_id', $intent->id)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'success',
                    'gateway_payment_id' => $intent->latest_charge,
                ]);

                $payment->order->update(['status' => 'paid']);
            }
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $intent = $event->data->object;
            $payment = Payment::where('gateway_order_id', $intent->id)->first();
            if ($payment) {
                $payment->update(['status' => 'failed']);
                $payment->order->update(['status' => 'failed']);
            }
        }

        return response()->json(['ok' => true]);
    }

    // public function handle(Request $request)
    // {
    //     $event = Webhook::constructEvent(
    //         $request->getContent(),
    //         $request->header('Stripe-Signature'),
    //         config('services.stripe.webhook_secret')
    //     );

    //     if ($event->type === 'checkout.session.completed') {

    //         $session = $event->data->object;

    //         $payment = Payment::where('gateway_order_id', $session->id)->first();

    //         if ($payment) {
    //             $payment->update([
    //                 'status' => 'success',
    //                 'gateway_payment_id' => $session->payment_intent,
    //             ]);

    //             $payment->order->update([
    //                 'status' => 'paid'
    //             ]);
    //         }
    //     }

    //     return response()->json(['ok' => true]);
    // }

    public function handle(Request $request)
    {
        $event = Webhook::constructEvent(
            $request->getContent(),
            $request->header('Stripe-Signature'),
            config('services.stripe.webhook_secret')
        );

        if ($event->type !== 'checkout.session.completed') {
            return response()->json(['ignored' => true]);
        }

        $session = $event->data->object;

        $payment = Payment::where('gateway_order_id', $session->id)->first();

        if (!$payment || $payment->status === 'success') {
            return response()->json(['ok' => true]);
        }

        $payment->update([
            'status' => 'success',
            'gateway_payment_id' => $session->payment_intent,
        ]);

        $payment->order->update([
            'status' => 'paid',
        ]);

        return response()->json(['ok' => true]);
    }


}