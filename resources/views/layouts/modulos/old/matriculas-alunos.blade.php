<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>Módulo Admin - @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('/css/plugins/sweetalert.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/plugins/toastr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @section('stylesheets')
    @show
</head>

<body class="skin-blue-light sidebar-mini sidebar-collapse">

<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="{{route('matriculas-alunos.index.alunos')}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>M</b>AT</span>
            <!-- logo for regular state and mobile devices -->
            <span style="background-color: #3c8dbc" class="logo-lg"><b>Matrículas</span>
        </a>

        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{url('/')}}/img/avatar.png" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ Auth::guard('matriculas-alunos')->user()->nome }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{url('/')}}/img/avatar.png" alt="User Image" class="img-circle">
                                <p>{{ Auth::guard('matriculas-alunos')->user()->nome }}</p>
                            </li>
                            <li class="user-footer">

                                <div class="pull-right">
                                    <a href="{{ route('auth.matriculas-alunos.logout') }}" class="btn btn-default btn-flat">
                                        <i class="fa fa-sign-out"></i> Sair
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Left side column. contains the main navigation menu-->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar" style="height: auto;">

            <ul class="sidebar-menu tree" data-widget="tree">


                <li class="active">
                    <a href="{{route('matriculas-alunos.index.alunos')}}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        <span class="pull-right-container">

            </span>
                    </a>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('title')
                <small>@yield('subtitle')</small>
            </h1>
            <div class="actionbutton">
                @yield('actionButton')
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
    </div><!-- /.content-wrapper -->

    <!-- Footer bar. -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Versão</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2006-{{ date('Y') }} <a href="http://www.uemanet.uema.br">UemaNet</a>.</strong> Todos os direitos reservados.
    </footer>

</div><!-- ./wrapper -->

<!-- JQUERY-->
<script src="{{ asset('/js/jquery-2.2.3.min.js')}}"></script>
<script src="{{ asset('/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/app.min.js')}}"></script>
<script src="{{ asset('/js/plugins/sweetalert.min.js')}}"></script>
<script src="{{ asset('/js/plugins/toastr.min.js')}}"></script>
<script src="{{ asset('/js/harpia.js')}}"></script>

{!! Flash::render() !!}

@section('scripts')

@show
</body>
</html>
