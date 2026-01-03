<?php

namespace App\Services;
use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\Payment;

class RazorpayService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createOrder(Order $order, Payment $payment)
    {
        // Logic to create Razorpay order
        $api = new Api(
            config('services.razorpay.key'), 
            config('services.razorpay.secret')
        );

        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => $order->total_amount * 100, // Amount in paise
            'currency' => 'INR',
        ]);

        $payment->update([
            'gateway_order_id' => $razorpayOrder['id']
        ]);

        return view('pages.payments.razorpay', compact('order', 'payment'));
    }
}