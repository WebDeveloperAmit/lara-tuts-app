<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\RazorpayController;

Route::get('/', function () {
    return redirect(url(app()->getLocale() . '/login'));
});

Route::group([
    'prefix' => '{locale?}/checkout', 
    'as' => 'checkout.',
    'middleware' => ['auth.check']
], function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
    Route::get('/failed/{order}', [CheckoutController::class, 'failed'])->name('failure');
    Route::post('/retry/{order}', [CheckoutController::class, 'retry'])->name('retry');
});

Route::post('/razorpay/verify', [RazorpayController::class, 'verify'])
    ->name('razorpay.verify');


// Authentication Routes
Route::get('/{locale?}/login', function () {
    return view('pages.auth.login');
})->name('login');

Route::get('/{locale?}/register', function () {
    return view('pages.auth.register');
})->name('register');

// Social Authentication Routes
Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::controller(FacebookController::class)->group(function(){
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

Route::post('/login/process', [CheckoutController::class, 'loggedInProcess'])->name('login.process');
Route::post('/register/process', [CheckoutController::class, 'registerProcess'])->name('register.process');

// Logout Route
Route::get('/logout', [CheckoutController::class, 'logout'])->name('logout');

// Fallback Route for undefined routes
Route::fallback(function () {
    return redirect(url(app()->getLocale() . '/login'));
});