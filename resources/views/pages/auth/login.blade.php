@extends('pages.auth.layout')
@section('title', 'Login')
@section('content')
    <div class="auth-card">

        <h1>{{ __('messages.welcome_back') }}</h1>
        <p>{{ __('messages.login_to_continue_to_checkout') }}</p>

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <div class="form-group">
                <label>{{ __('messages.email_address') }}</label>
                <input 
                type="email" 
                name="email"
                value="{{ old('email') }}"
                class="@error('email') is-invalid @enderror"
                >
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.password') }}</label>
                <input 
                type="password" 
                name="password"
                class="@error('password') is-invalid @enderror"
                >
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">{{ __('messages.login') }}</button>
        </form>

        <div class="divider">{{ __('messages.or') }}</div>

        <a class="social-btn google" href="{{ route('auth.google') }}">
            <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png">
        </a>

        <a class="social-btn facebook" href="{{ route('auth.facebook') }}">
            <img src="https://www.facebook.com/images/fb_icon_325x325.png"
                style="height:40px; border-radius:4px;">
        </a>


        {{-- <a href="" class="social-btn facebook">
            📘 {{ __('messages.continue_with_facebook') }}
        </a> --}}

        {{-- <a href="{{ route('auth.facebook') }}" class="social-btn facebook">
            📘 Continue with Facebook
        </a> --}}

        <div class="auth-footer">
            {{ __('messages.dont_have_account') }}
            <a href="{{ route('register', ['locale' => app()->getLocale()]) }}">{{ __('messages.sign_up') }}</a>
        </div>

    </div>
@endsection

@push('css')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            margin: 0;
            padding: 0;
        }

        /* .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        } */

        .auth-container {
            position: relative;  /* Add this */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 8px;
        }

        p {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 13px;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-primary {
            background: #4f46e5;
            color: #fff;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .divider {
            text-align: center;
            font-size: 13px;
            color: #9ca3af;
            margin: 20px 0;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #111827;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .social-btn.google { background: #fff; }
        .social-btn.facebook { background: #1877f2; color: #fff; border: none; }

        .auth-footer {
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
        }

        .auth-footer a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }

        .language-selector {
            position: absolute;
            top: 20px;      /* adjust vertical position */
            right: 20px;    /* adjust horizontal position */
            z-index: 1000;  /* above other content */
        }
    </style>
@endpush