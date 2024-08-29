<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;

/**
 * Route for Dashboard and Root
 */
Route::redirect('/', 'dashboard');
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/**
 * Route for Authentication
 */
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:3,5'); // User is allowed a maximum of 3 requests to the login route within 5-minute.
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

/**
 * Route for payment
 */
Route::get('payment/checkout', [PaymentController::class, 'createCheckoutSession'])->name('payment.checkout');
Route::get('payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');



