<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    
}