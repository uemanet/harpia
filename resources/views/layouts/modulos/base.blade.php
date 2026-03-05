<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>Módulo Admin - @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('img/logo-mini.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />

    @yield('stylesheets')
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('layouts.includes.navbar.main', [ 'noaside' => false ])

        <!-- Left side column. contains the main navigation menu-->
        @include('layouts.includes.sidebar.left')

        @yield('modulo-content')

        <!-- Footer bar. -->
        @include('layouts.includes.footer.main')

    </div>
    <!-- ./wrapper -->

    <script src="{{ asset('/js/app.js') }}"></script>
    {!! Flash::render() !!}
    @yield('scripts')
</body>
</html>
