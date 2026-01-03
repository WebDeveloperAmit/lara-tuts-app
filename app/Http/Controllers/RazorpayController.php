<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{

    public function verify(Request $request)
    {
        $payment = Payment::where(
            'gateway_order_id',
            $request->razorpay_order_id
        )->firstOrFail();

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        try {
            // Verify signature
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            // PAYMENT SUCCESS
            $payment->update([
                'gateway_payment_id' => $request->razorpay_payment_id,
                'gateway_signature'  => $request->razorpay_signature,
                'status' => 'success',
                'meta' => $request->all(),
            ]);

            $payment->order->update([
                'status' => 'paid'
            ]);

            return response()->json([
                'redirect_url' => route('checkout.success', [
                    'locale' => app()->getLocale(),
                    'order' => $payment->order_id
                ])
            ]);

        } catch (\Exception $e) {

            $payment->update([
                'status' => 'failed',
                'meta' => ['error' => $e->getMessage()]
            ]);

            $payment->order->update([
                'status' => 'failed'
            ]);

            return response()->json([
                'redirect_url' => route('checkout.failure', [
                    'locale' => app()->getLocale(),
                    'order' => $payment->order_id
                ])
            ]);
        }
    }

}