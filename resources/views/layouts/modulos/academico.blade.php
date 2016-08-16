<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>Módulo ADMIN @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/plugins/sweetalert.css') }}" />

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

    <header class="main-header">
        <a href="{{url('/')}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>G</b>ER</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Acadêmico</b></span>
        </a>

        @include('layouts.includes.header_rightmenu')
    </header>

    <!-- Left side column. contains the main navigation menu-->
    @include('layouts.includes.left')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="box" style="margin-bottom:0px">
            <div class="box-header" style="padding-bottom:10px">
                <div class="col-md-6">
                    <section class="content-header" style="padding-top:10px">
                        <h1>
                            @yield('title')
                        </h1>
                        <small>@yield('subtitle')</small>
                    </section>
                </div>
                <div class="col-md-6 text-right">
                    @yield('actionButton')
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
    </div><!-- /.content-wrapper -->

    <!-- Footer bar. -->
    @include('layouts.includes.footer')

</div><!-- ./wrapper -->

<!-- JQUERY-->
<script src="{{ asset('/js/jQuery-2.2.0.min.js')}}"></script>
<script src="{{ asset('/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/app.min.js')}}"></script>
<script src="{{ asset('/js/plugins/sweetalert.min.js')}}"></script>
<script src="{{ asset('/js/harpia.js')}}"></script>

{!! Flash::render() !!}

@section('scripts')

@show
</body>
</html>
