<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">

    <title>{{config('system.title')}}</title>

    <!-- <link rel="stylesheet" href="{{ asset('/assets/vendor/fontawesome/css/font-awesome.min.css') }}"/> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/simple-line-icons/css/simple-line-icons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.css') }}" id="bscss"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/app.css') }}" id="maincss"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}" id="maincss"/>

    <link id="autoloaded-stylesheet" rel="stylesheet" href="{{ asset('/assets/css/theme-d.css')}}">

    @section('stylesheets')
    @show
</head>
<body class="layout-h">
    @include('layouts.includes.header')  
    
    <div class="wrapper">
        <section>@yield('content')</section>
    </div>
    
    <!-- MODERNIZR-->
    <script src="{{ asset('/assets/vendor/modernizr/modernizr.js')}}"></script>
    <!-- JQUERY-->
    <script src="{{ asset('/assets/vendor/jquery/dist/jquery.js')}}"></script>
    <!-- BOOTSTRAP-->
    <script src="{{ asset('/assets/vendor/bootstrap/dist/js/bootstrap.js')}}"></script>
    <!-- STORAGE API-->
    <script src="{{ asset('/assets/vendor/jQuery-Storage-API/jquery.storageapi.js')}}"></script>
    <!-- PARSLEY-->
    <script src="{{ asset('/assets/vendor/parsleyjs/dist/parsley.min.js') }}"></script>

    <!-- =============== APP SCRIPTS ===============-->
    <script src="{{ asset('/assets/js/app.js') }}"></script>
    
    @section('scripts')
    
    @show
</body>
</html>