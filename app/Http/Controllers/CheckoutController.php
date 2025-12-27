<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function logout() 
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('login');
        }
    }
    
}