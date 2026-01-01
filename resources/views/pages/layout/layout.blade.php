<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    @stack('css')

    <style>
        .lang-btn {
            background: #fff;
            border: 1px solid #ddd;
            padding: 8px 14px;
            cursor: pointer;
            font-weight: 600;
        }

        .lang-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            list-style: none;
            margin: 0;
            padding: 0;
            min-width: 140px;
            box-shadow: 0 4px 10px rgba(0,0,0,.1);
            z-index: 1000;
        }

        .lang-dropdown li a {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
        }

        .lang-dropdown li a:hover,
        .lang-dropdown li a.active {
            background: #f5f5f5;
            font-weight: 600;
        }

        .language-selector:hover .lang-dropdown {
            display: block;
        }
    </style>

</head>
<body>

<div class="container">

    @if (Request::is(app()->getLocale() . '/checkout'))
        <div class="logout_div">
            <a href="{{ route('logout') }}" class="logout_btn">{{ __('messages.logout') }}</a>
        </div>
        @php
            // echo Request::url();
            // echo "<hr />";
            // echo url()->current();
        @endphp
    @endif

    <!-- Language dropdown placed here -->
    <div class="language-selector">
        <button class="lang-btn">
            {{ strtoupper(app()->getLocale()) }} ▾
        </button>

        <ul class="lang-dropdown">
            @foreach (config('app.supported_languages') as $locale)
                <li>
                    <a href="{{ url(str_replace(Request::segment(1), $locale, Request::url())) }}" class="{{ app()->getLocale() == $locale ? 'active' : '' }}">
                        {{ __('messages.' . $locale) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @yield('content')

</div>

@stack('js')

</body>
</html>
