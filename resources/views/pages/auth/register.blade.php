<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
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

        .language-selector select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1.5px solid #4f46e5;
            font-size: 14px;
            font-weight: 500;
            background-color: white;
            color: #4f46e5;
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .language-selector select:hover,
        .language-selector select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 8px rgba(99, 102, 241, 0.5);
            outline: none;
        }

    </style>
</head>
<body>

<div class="auth-container">

    <!-- Language dropdown placed here -->
    <div class="language-selector">
        <select onchange="changeLanguage(this.value)">
            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
            <option value="bn" {{ app()->getLocale() == 'bn' ? 'selected' : '' }}>Bengali</option>
            <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>Hindi</option>
        </select>
    </div>

    <div class="auth-card">

        <h1>{{ __('messages.create_account') }}</h1>
        <p>{{ __('messages.login_to_continue_to_checkout') }}</p>

        <form method="POST" action="">
            @csrf

            <div class="form-group">
                <label>{{ __('messages.full_name') }}</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.email_address') }}</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.password') }}</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.confirm_password') }}</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button class="btn btn-primary">{{ __('messages.create_account') }}</button>
        </form>

        <div class="divider">{{ __('messages.or') }}</div>

        <a href="" class="social-btn google">
            🔎 {{ __('messages.continue_with_google') }}
        </a>

        <a href="" class="social-btn facebook">
            📘 {{ __('messages.continue_with_facebook') }}
        </a>

        {{-- <a href="{{ route('auth.google') }}" class="social-btn google">
            🔎 Sign up with Google
        </a>

        <a href="{{ route('auth.facebook') }}" class="social-btn facebook">
            📘 Sign up with Facebook
        </a> --}}

        <div class="auth-footer">
            {{ __('messages.already_have_an_account?') }}
            <a href="{{ route('login') }}">{{ __('messages.login') }}</a>
        </div>

    </div>
</div>

<script>
    function changeLanguage(locale) {
        window.location.href = `/register/${locale}`;
    }
</script>

</body>
</html>
