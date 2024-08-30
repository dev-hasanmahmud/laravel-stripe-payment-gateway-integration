<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $paymentsReport = Payment::all();
        $selfPaymentReport = Payment::where('user_id', auth()->user()->id)->get();

        return view('dashboard', compact('paymentsReport', 'selfPaymentReport'));
    }
}
