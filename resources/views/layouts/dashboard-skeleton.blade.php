<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'Home') &mdash; {{ env('APP_NAME', 'PhotoLife') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/iziToast.min.css') }}">
    @stack('stylesheet')
</head>

<body>
<div id="app">
    @yield('app')
</div>
<script src="{{ asset('assets/dashboard/js/manifest.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/vendor.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/app.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/iziToast.min.js') }}"></script>
@stack('javascript')
</body>
</html>
