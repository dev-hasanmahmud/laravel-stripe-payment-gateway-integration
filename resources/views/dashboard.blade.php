@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h2>Welcome, {{ Auth::user()->name }}</h2>
    <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
</div>
@endsection
