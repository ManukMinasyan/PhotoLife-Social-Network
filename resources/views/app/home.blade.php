<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PhotoLife') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
</head>
<body>
<div id="app"></div>
<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
