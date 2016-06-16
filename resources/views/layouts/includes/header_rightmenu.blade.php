<nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    <i class="fa fa-user"></i> {{ Auth::user()->pessoa->pes_nome }}
                </a>
                <ul class="dropdown-menu" style="width:100px;height:autopx">
                    <li>
                        <ul class="menu">
                            <li>
                                <a href="{{url('/')}}/seguranca/profile">
                                    <i class="fa fa-edit text-aqua"></i> Perfil
                                </a>
                            </li>
                            <li>
                                <a href="{{url('/')}}/logout">
                                    <i class="fa fa-close text-red"></i> Sair
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>