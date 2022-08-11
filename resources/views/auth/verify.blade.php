@extends('layouts.auth_app')

@section('content')
<div class="wrapper-page">
    <div class="card-box">
        <div class="panel-heading">
            <h3 class="text-center"> Verify your email address</h3>
        </div>

        <div class="card-body">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
        </div>
    </div>
</div>

@endsection
