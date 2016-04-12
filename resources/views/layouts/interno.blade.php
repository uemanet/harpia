<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>Módulo ADMIN @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @section('stylesheets')
    @show
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Header bar. contains the logo and menubar -->
    @include('layouts.includes.header')

    <!-- Left side column. contains the main navigation menu-->
    @include('layouts.includes.left')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>@yield('title')</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
    </div><!-- /.content-wrapper -->

    <!-- Footer bar. -->
    @include('layouts.includes.footer')

</div><!-- ./wrapper -->

<!-- JQUERY-->
<script src="{{ asset('/javascript/jQuery-2.2.0.min.js')}}"></script>
<script src="{{ asset('/javascript/bootstrap.min.js')}}"></script>
<script src="{{ asset('/javascript/app.min.js')}}"></script>

@section('scripts')

@show
</body>
</html>