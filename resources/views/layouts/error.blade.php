<!doctype html>
<html lang="en" class="light-theme">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ URL::asset('build/images/favicon-32x32.png') }}" type="image/png" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Plugins -->
    <link href="{{ URL::asset('build/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/plugins/metismenu/metisMenu.min.css') }}" rel="stylesheet" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Vite Assets -->
    @vite(['resources/sass/main.scss', 'resources/sass/pages/error.scss', 'resources/js/main.js'])

    <title>@yield('title') - {{ config('app.name') }}</title>
</head>

<body>

    <div class="d-flex flex-column min-vh-100">
        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
            <div class="error-card">
                <div class="decoration-circle circle-1"></div>
                <div class="decoration-circle circle-2"></div>
                <div class="content-wrapper">
                    <h1 class="error-code">@yield('code')</h1>
                    <h2 class="error-message">@yield('message')</h2>
                    <p class="error-description">@yield('description')</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ url()->previous() }}"
                            class="btn btn-outline-secondary btn-home px-4 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <a href="{{ url('/') }}"
                            class="btn btn-primary btn-home px-4 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-house-door me-2"></i>Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @unless(View::hasSection('hide_footer'))
            @include('layouts.footer')
        @endunless
    </div>

    <!-- Plugins -->
    <script src="{{ URL::asset('build/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>