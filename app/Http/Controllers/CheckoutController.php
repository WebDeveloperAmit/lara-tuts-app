<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Payment;
use App\Services\RazorpayService;
use App\Services\StripeService;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('pages.checkout');
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:stripe,paypal,razorpay,cod'],
        ]);

        try {
            // Begin Transaction
            DB::beginTransaction();

            $lastOrderNumber = DB::table('orders')
                ->lockForUpdate()
                ->max(DB::raw("CAST(SUBSTRING(order_number, 5) AS UNSIGNED)"));

            $nextNumber = $lastOrderNumber ? $lastOrderNumber + 1 : 10001;
            $orderNumber = 'ORD-' . $nextNumber;
            
            // Create Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'order_number' => $orderNumber,
                'total_amount' => $request->total_amount,
                'status' => 'payment_pending',
            ]);

            $payment = Payment::create([
                'order_id' => $order->id,
                'gateway' => $request->payment_method,
                'amount' => $order->total_amount,
                'currency' => 'INR',
            ]);

            DB::commit(); // Commit Transaction
            return $this->handlePayment($order, $payment);
        } catch (\Exception $ex) {
            DB::rollBack(); // Rollback Transaction
            Log::error('Checkout Process Error', ['error' => $ex->getMessage()]);
            flash()->error(__('messages.checkout_process_error'));
            return redirect()->route('checkout.index', ['locale' => app()->getLocale()]);
        }
    }

    private function handlePayment(Order $order, Payment $payment) // Type hinting added. PHP immediately ensures that $order is an Order object and $payment is a Payment object. $order is an instance of the Order model representing the order being processed. $payment is an instance of the Payment model representing the payment details for that order.
    {
        switch ($payment->gateway) {
            case 'razorpay':
                // Handle Razorpay Payment
                return app(RazorpayService::class)->createOrder($order, $payment);
            case 'stripe':
                // Handle Stripe Payment
                return $this->stripePayment($order, $payment);
            case 'cod':
                $order->update(['status' => 'paid']);
                $payment->update(['status' => 'success']);
                return redirect()->route('checkout.success', ['locale' => app()->getLocale(), 'uuid' => $order->uuid]);
            default:
                abort(400, __('messages.unsupported_payment_method'));
        }
    }

    public function retry(Order $order) // Type hinting added. PHP immediately ensures that $order is an Order object. $order is an instance of the Order model representing the order being retried.
    {
        // Create NEW Razorpay order
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $razorpayOrder = $api->order->create([
            'amount' => $order->total_amount * 100,
            'currency' => 'INR',
            'receipt' => 'retry_' . $order->id,
        ]);

        // Create new payment entry
        $payment = Payment::create([
            'order_id' => $order->id,
            'gateway_order_id' => $razorpayOrder['id'],
            'status' => 'pending',
        ]);

        // Redirect to checkout page with new payment
        return view('pages.retry-checkout-payment', compact('order', 'payment'));
    }

    public function stripePayment(Order $order, Payment $payment)
    {
        // Here you would typically create a Stripe Checkout Session or Payment Intent
        // and return the necessary information to the frontend to complete the payment.
        // For simplicity, we'll just return a view with order and payment details.
        $stripe = app(StripeService::class);
        $intent = $stripe->createPaymentIntent($order);

        // Update payment with Stripe info
        $payment->update([
            'gateway_order_id' => $intent->id,
            'status' => 'pending',
            'meta' => $intent->toArray(),
        ]);

        return view('pages.payments.stripe-checkout', compact('order', 'payment', 'intent'));
    }

    public function success($uuid)
    {
        $uuid = request()->route('uuid'); // Get 'uuid' parameter from the route
        $order = Order::with('payment')->where('uuid', $uuid)->firstOrFail();
        return view('pages.payment-success', compact('order'));
    }

    public function failed($uuid)
    {
        $uuid = request()->route('uuid'); // Get 'uuid' parameter from the route
        $order = Order::with('payment')->where('uuid', $uuid)->firstOrFail();
        return view('pages.payment-failure', compact('order'));
    }

    // Stripe Payment Failed Handler
    public function stripePaymentFailed($uuid)
    {
        $uuid = request()->route('uuid'); // Get 'uuid' parameter from the route
        $order = Order::where('uuid', $uuid)->firstOrFail();
        return view('pages.payments.payment-failed', compact('order'));
    }

    public function stripePaymentProcessing()
    {
        return view('pages.payments.stripe-payment-processing');
    }


    public function loggedInProcess(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            flash()->error(__('messages.email_not_found'));
            return redirect()->route('login')->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            flash()->error(__('messages.password_incorrect'));
            return redirect()->route('login')->withInput();
        }

        Auth::login($user);
        flash()->success(__('messages.logged_in_successfully'));
        $request->session()->regenerate(); // Regenerate session to prevent fixation attacks
        return redirect()->intended(route('checkout.index', ['locale' => app()->getLocale()]));
    }

    public function registerProcess(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        // dd($validatedData);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        Auth::login($user);
        flash()->success(__('messages.registration_successful'));
        $request->session()->regenerate(); // Regenerate session to prevent fixation attacks
        return redirect()->intended(route('checkout.index', ['locale' => app()->getLocale()]));
    }

    public function logout(Request $request) 
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate(); // Invalidate the session data
            $request->session()->regenerateToken(); // Regenerate CSRF token
            flash()->success(__('messages.logged_out_successfully'));
            return redirect()->route('login', ['locale' => app()->getLocale()]);
        }
    }
    
}