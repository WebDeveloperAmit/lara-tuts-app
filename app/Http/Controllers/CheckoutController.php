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
use Illuminate\Support\Str;
use App\Services\RazorpayService;

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
            
            // Create Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'order_number' => Str::uuid(),
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
            Log::error('Checkout Process Error: ' . $ex->getMessage());
            flash()->error(__('messages.checkout_process_error'));
            return redirect()->route('checkout.index', ['locale' => app()->getLocale()]);
        }
    }

    private function handlePayment($order, $payment)
    {
        switch ($payment->gateway) {
            case 'razorpay':
                // Handle Razorpay Payment
                return app(RazorpayService::class)->createOrder($order, $payment);
            default:
            abort(400, __('messages.unsupported_payment_method'));
        }
    }

    public function success($order)
    {
        return view('pages.payment-success', compact('order'));
    }

    public function failed($order)
    {
        return view('pages.payment-failure', compact('order'));
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