<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    @stack('css')

</head>
<body>

<div class="container">

    @if (Request::is('checkout'))
        <div class="logout_div">
            <a href="{{ route('logout') }}" class="logout_btn">{{ __('messages.logout') }}</a>
        </div>
    @endif

    <!-- Language dropdown placed here -->
    <div class="language-selector">
        <select onchange="changeLanguage(this.value)">
            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
            <option value="bn" {{ app()->getLocale() == 'bn' ? 'selected' : '' }}>Bengali</option>
            <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>Hindi</option>
        </select>
    </div>

    @yield('content')

</div>

@stack('js')

</body>
</html>
