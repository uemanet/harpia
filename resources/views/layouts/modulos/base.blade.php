<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

    <title>Módulo Admin - @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('img/logo-mini.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />

    @yield('stylesheets')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @include('layouts.includes.navbar.main', [ 'noaside' => false ])

        <!-- Left side column. contains the main navigation menu-->
        @include('layouts.includes.sidebar.left')

        <main class="app-main bg-white" >
            @yield('modulo-content')
        </main>

        <!-- Footer bar. -->
        @include('layouts.includes.footer.main')

    </div>
    <!-- ./wrapper -->

    <script src="{{ asset('/js/app.js') }}"></script>
{{--    {!! Flash::render() !!}--}}
    @yield('scripts')
</body>
</html>
