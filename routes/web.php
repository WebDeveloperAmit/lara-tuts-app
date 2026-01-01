<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\FacebookController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group([
    'prefix' => 'checkout', 
    'as' => 'checkout.', 
    'where' => ['locale' => 'en|bn|hi'],
    'middleware' => ['auth.check']
], function () {
    Route::get('/{locale?}', [CheckoutController::class, 'index'])->name('index');
    Route::post('process/{locale?}', [CheckoutController::class, 'process'])->name('process');
    Route::get('success/{locale?}', [CheckoutController::class, 'success'])->name('success');
    Route::get('failure/{locale?}', [CheckoutController::class, 'failure'])->name('failure');
});

// Authentication Routes
Route::get('/login/{locale?}', function () {
    return view('pages.auth.login');
})->name('login');

Route::get('/register/{locale?}', function () {
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
    return redirect()->route('login');
});