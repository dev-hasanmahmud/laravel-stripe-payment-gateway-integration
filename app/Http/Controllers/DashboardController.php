<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $paymentsReport = Payment::all();

        return view('dashboard', compact('paymentsReport'));
    }
}
