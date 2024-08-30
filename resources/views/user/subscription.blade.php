@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container col-md-5">
    <h4>Account Monthly Subscribe Plan</h4>
    <form action="{{ route('payment.process') }}" method="POST" class="p-4 border rounded bg-light">
    	@csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

    	<p>Each user need to pay USD 10 per month to activate their account.</p>
    	<input type="hidden" name="name" value="Account Monthly Subscribe Plan">
    	<input type="hidden" name="quantity" value="1">
    	<input type="hidden" name="amount" value="10">
        <button type="submit" class="btn btn-success">Pay $10</button>
    </form>
</div>
@endsection
