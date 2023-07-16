<nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0) }}" class="user-image" alt="User Image">
                    <span class="hidden-xs">{{ Auth::user()->pessoa->pes_nome }} @if(Auth::user()->pessoa->pes_itt_id) - {{ Auth::user()->pessoa->getInstituicaoSigla(Auth::user()->pessoa->pes_itt_id) }} @endif</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-header">
                        <img src="{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0) }}" alt="User Image" class="img-circle">
                        <p>{{ Auth::user()->pessoa->pes_nome }} @if(Auth::user()->pessoa->pes_itt_id) - {{ Auth::user()->pessoa->getInstituicaoSigla(Auth::user()->pessoa->pes_itt_id) }} @endif</p>
                    </li>
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="{{ route('seguranca.profile.index') }}" class="btn btn-default btn-flat">
                                <i class="fa fa-edit"></i> Perfil
                            </a>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('auth.logout') }}" class="btn btn-default btn-flat">
                                <i class="fa fa-sign-out"></i> Sair
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>