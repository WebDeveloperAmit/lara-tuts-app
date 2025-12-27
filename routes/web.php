<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GoogleController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group([
    'prefix' => 'checkout', 
    'as' => 'checkout.', 
    'middleware' => ['auth.check']
], function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('process', [CheckoutController::class, 'process'])->name('process');
    Route::get('success', [CheckoutController::class, 'success'])->name('success');
    Route::get('failure', [CheckoutController::class, 'failure'])->name('failure');
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