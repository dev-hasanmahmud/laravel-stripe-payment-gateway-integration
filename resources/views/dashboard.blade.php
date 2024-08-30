@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h2>Welcome, {{ Auth::user()->name }}</h2>
    <a href="{{ route('payment.form') }}" class="btn btn-primary">Buy Account Subscription for 1 month</a>
</div>
@endsection
