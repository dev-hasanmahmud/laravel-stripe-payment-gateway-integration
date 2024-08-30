<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function showPaymentForm()
    {
        return view('user.subscription');
    }

    public function processPayment(Request $request)
    {
        // Use request data directly
        $name = $request->input('name');
        $amount = $request->input('amount');
        $quantity = $request->input('quantity');

        // Initialize Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Create a new Checkout Session
            $session = StripeSession::create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => ['name' => $name],
                            'unit_amount' => $amount * 100, // Amount in cents
                        ],
                        'quantity' => $quantity,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('payment.success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
            ]);

            // Store session data in the session
            session()->put('payment_details', [
                'name' => $name,
                'quantity' => $quantity,
                'amount' => $amount,
                'currency' => 'usd',
            ]);

            // Redirect to the Checkout page
            return redirect($session->url);
        } catch (\Exception $e) {
            // Handle error
            return redirect()->route('payment.cancel')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function success(Request $request)
    {
        // Initialize Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Retrieve the Checkout Session
            $session = StripeSession::retrieve($request->session_id);

            if ($request->has('session_id')) {
                // Store payment information
                $payment = new Payment();
                $payment->user_id = Auth::id();
                $payment->payment_id = $session->id;
                $payment->name = session('payment_details.name');
                $payment->quantity = session('payment_details.quantity');
                $payment->amount = session('payment_details.amount');
                $payment->currency = session('payment_details.currency');
                $payment->payer_name = $session->customer_details->name ?? 'N/A';
                $payment->payer_email = $session->customer_details->email ?? 'N/A';
                $payment->payment_status = $session->payment_status;
                $payment->payment_method = 'Stripe';
                $payment->save();

                // Update User Subscription
                $user = Auth::user();
                $user->account_active = 1;
                $user->account_expires = now()->addMonth(); // Adds one month from now
                $user->save();

                // Clear session data
                session()->forget('payment_details');

                return view('user.success');

            } else {
                return redirect()->route('payment.cancel');
            }
        } catch (\Exception $e) {
            // Handle error
            return redirect()->route('payment.cancel')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function cancel()
    {
        return view('user.cancel');
    }
}
