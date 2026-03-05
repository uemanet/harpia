<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>{{config('system.title')}}</title>
    <link rel="shortcut icon" href="{{ asset('img/logo-mini.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />

    @yield('stylesheets')
</head>
<body class="hold-transition login-page">
    @yield('content')

    @include('layouts.includes.footer.clean')

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>