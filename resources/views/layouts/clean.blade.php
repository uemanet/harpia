<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('img/logo-mini.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />

    @yield('scripts')
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <header>
        @include('layouts.includes.navbar.main', [ 'noaside' => true ])
    </header>

    <div class="content-wrapper">
        @yield('content')
    </div>
</div>

@include('layouts.includes.footer.clean')

<script src="{{ asset('js/app.js') }}"></script>
{!! Flash::render() !!}

@yield('scripts')
</body>
</html>