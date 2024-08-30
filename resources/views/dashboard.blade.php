@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    @auth
    <h2>Welcome, {{ auth()->user()->email }}</h2>

    @if ((auth()->user()->account_expires ? \Carbon\Carbon::parse(auth()->user()->account_expires) : null) && auth()->user()->account_expires)
        @if (!now()->greaterThan(auth()->user()->account_expires) && auth()->user()->account_active != '0' && auth()->user()->role != '1')
            <div class="row">
                <div class="col-3 card card-body">
                    <button class="btn btn-success">Activated Premium Account</button>
                    <br/>
                    <p>When you use premium plan, you can get premium account services! (Like: Your payment history)</p>
                </div>
            </div>
            <br/>
        @else
            @if(auth()->user()->role != '1')
            <div class="row">
                <div class="col-3 card card-body">
                    <button class="btn btn-danger">Account expires!</button>
                    <br/>
                    <a href="{{ route('payment.form') }}" class="btn btn-primary">Buy Premium Account</a>
                    <br/>
                    <p>When you use premium plan, you can get premium account services! (Like: Your payment history)</p>
                </div>
            </div>
            <br/>    
            @endif  
        @endif
    @endif

    @if(auth()->user()->account_active == '0' && auth()->user()->role != '1')
    <div class="row">
        <div class="col-3 card card-body m-2">
            <button class="btn btn-success">Free Plan (Default)</button>
            <br/>
            <p>When you use free plan, you can only login account!</p>
        </div>
        <div class="col-3 card card-body">
            <a href="{{ route('payment.form') }}" class="btn btn-primary">Buy Premium Account</a>
            <br/>
            <p>When you use premium plan, you can get premium account services! (Like: Your payment history)</p>
        </div>
    </div>
    <br/>
    @endif

    @if(auth()->user()->role == '1')
    <div class="card card-body">
        <h4>All User payment reports</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Payer Name</th>
                    <th>Payer Email</th>
                    <th>Payment Method</th>
                    <th>Payment Date</th>
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
                    <td>{{ $payment->created_at ?? '' }}</td>
                    <td><span class="text-success">{{ $payment->payment_status ?? '' }}</span></td>
                </tr>
                @empty
                    <td colspan="7" class="text-center">Data not found!</td>
                @endforelse
            </tbody>
        </table>
    </div>
    @else
        @if ((auth()->user()->account_expires ? \Carbon\Carbon::parse(auth()->user()->account_expires) : null) && auth()->user()->account_expires)
        @if (!now()->greaterThan(auth()->user()->account_expires) && auth()->user()->account_active != '0' && auth()->user()->role != '1')
        <div class="card card-body">
            <h4>Your payment History</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Amount</th>
                        <th>Currency</th>
                        <th>Payer Name</th>
                        <th>Payer Email</th>
                        <th>Payment Method</th>
                        <th>Payment Date</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($selfPaymentReport as $selfPayment)
                    <tr>
                        <td>{{ $selfPayment->name ?? '' }}</td>
                        <td>{{ $selfPayment->amount ?? '' }}</td>
                        <td>{{ $selfPayment->currency ?? '' }}</td>
                        <td>{{ $selfPayment->payer_name ?? '' }}</td>
                        <td>{{ $selfPayment->payer_email ?? '' }}</td>
                        <td>{{ $selfPayment->payment_method ?? '' }}</td>
                        <td>{{ $selfPayment->created_at ?? '' }}</td>
                        <td><span class="text-success">{{ $selfPayment->payment_status ?? '' }}</span></td>
                    </tr>
                    @empty
                        <td colspan="7" class="text-center">Data not found!</td>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif
        @endif
    @endif
    @endauth
</div>
@endsection
