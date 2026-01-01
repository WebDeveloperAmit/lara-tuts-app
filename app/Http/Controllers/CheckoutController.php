<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('pages.checkout');
    }

    public function process(Request $request)
    {
        
    }

    public function success()
    {
        return view('pages.payment-success');
    }

    public function failure()
    {
        return view('pages.payment-failure');
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