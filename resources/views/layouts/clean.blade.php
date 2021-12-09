<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/plugins/toastr.min.css') }}" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @section('stylesheets')
    @show
</head>

<body class="hold-transition skin-blue layout-top-nav">

    <div class="wrapper">

        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{url('/')}}" style="padding-top:2px;background-color:#E9F1F5" class="navbar-brand">
                            <img src="{{url('/')}}/img/logo.png" style="height:47px" />
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                    <nav class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0) }}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{ Auth::user()->pessoa->pes_nome }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header">
                                        <img src="{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0) }}" alt="User Image" class="img-circle">
                                        <p>{{ Auth::user()->pessoa->pes_nome }}</p>
                                    </li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="{{url('/')}}/seguranca/profile" class="btn btn-default btn-flat">
                                                <i class="fa fa-edit"></i> Perfil
                                            </a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{url('/')}}/logout" class="btn btn-default btn-flat">
                                                <i class="fa fa-sign-out"></i> Sair
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </ul>
                    </nav>
                </div>
            </nav>
        </header>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- JQUERY-->
    <script src="{{ asset('/js/jQuery-2.2.0.min.js')}}"></script>
    <script src="{{ asset('/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/app.min.js')}}"></script>
    <script src="{{ asset('/js/plugins/toastr.min.js')}}"></script>
    <script src="{{ asset('/js/harpia.js')}}"></script>
    {!! Flash::render() !!}

    @yield('scripts')
</body>
</html>