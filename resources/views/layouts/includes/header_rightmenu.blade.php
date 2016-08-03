<nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{url('/')}}/img/avatar.png" class="user-image" alt="User Image">
                    <span class="hidden-xs">{{ Auth::user()->pessoa->pes_nome }}</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-header">
                        <img src="{{url('/')}}/img/avatar.png" alt="User Image" class="img-circle">
                        <p>{{ Auth::user()->pessoa->pes_nome }}</p>
                    </li>
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="{{url('/')}}/seguranca/profile" class="btn btn-default btn-flat">
                                <i class="fa fa-edit"></i> Profile
                            </a>
                        </div>
                        <div class="pull-right">
                            <a href="{{url('/')}}/logout" class="btn btn-default btn-flat">
                                <i class="fa fa-sign-out"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>