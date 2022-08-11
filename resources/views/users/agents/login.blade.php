@extends('layouts.auth_app')

@section('content')

@if (\Session::has('error'))
    <span class="invalid-feedback text-danger" role="alert">
        <strong>{!! \Session::get('error') !!}</strong>
    </span>
@endif


<form method="POST" action="{{ route('signin') }}" class="sign-box">
@csrf
    <div class="sign-avatar">
        <img src="{{ asset('assets/img/avatar-sign.png') }}" alt="">
    </div>
    <header class="sign-title">Agent Signin</header>
<div class="form-group">
<label for="email" class=" col-form-label">{{ __('E-Mail Address') }}</label>

    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

    @if ($errors->has('email'))
        <span class="invalid-feedback text-danger" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
</div>

<div class="form-group">
<label for="password" class=" col-form-label">{{ __('Password') }}</label>
    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" requireds>

    @if ($errors->has('password'))
        <span class="invalid-feedback text-danger" role="alert">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mb-0">
    <button type="submit" class="btn btn-primary btn-sm btn-block">
        {{ __('Login') }}
    </button>
    <hr>
</div>
</form>

@endsection
