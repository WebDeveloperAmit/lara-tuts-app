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

    public function createOrder(Order $order, Payment $payment) // Type hinting added. PHP immediately ensures that $order is an Order object and $payment is a Payment object. $order is an instance of the Order model representing the order being processed. $payment is an instance of the Payment model representing the payment details for that order.
    {
        // Logic to create Razorpay order
        // Creating a new instance of the Razorpay API client using the configured key and secret.
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