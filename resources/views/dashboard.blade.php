@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h2>Welcome, {{ Auth::user()->name }}</h2>
    <a href="{{ route('payment.form') }}" class="btn btn-primary">Buy Account Subscription for 1 month</a>

    <table class="table">
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Payer Name</th>
                <th>Payer Email</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paymentsReport as $payment)
            <tr>
                <td>{{ $payment->name ?? '' }}</td>
                <td>{{ $payment->amount ?? '' }}</td>
                <td>{{ $payment->currency ?? '' }}</td>
                <td>{{ $payment->payer_name ?? '' }}</td>
                <td>{{ $payment->payer_email ?? '' }}</td>
                <td>{{ $payment->payment_method ?? '' }}</td>
                <td><span class="text-success">{{ $payment->payment_status ?? '' }}</span></td>
            </tr>
            @empty
                <td>Data not found!</td>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
