<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark" data-theme-locked="true">

<head>
    <!-- Dark Mode Enforcement: Auth pages are always Dark -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Dashboard Tools Netara By Tajawaz Solutions</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ URL::asset('build/images/favicon-32x32.png') }}" type="image/png">
    <meta name="recaptcha-key" content="{{ env('RECAPTCHA_SITE_KEY') }}">

    @include('layouts.head-css')
    @vite(['resources/css/pages/auth/auth.css'])
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
</head>

<body class="{{ isset($bodyClass) ? $bodyClass : '' }} d-flex flex-column min-vh-100">

    <div class="flex-grow-1">
        @yield('content')
    </div>

    <!-- Global Loader -->
    <div id="global-loader"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    @include('layouts.common-scripts')

    @stack('script')
</body>

</html>