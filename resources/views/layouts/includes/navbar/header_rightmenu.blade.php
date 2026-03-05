<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle text-white" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
        <img src="{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0) }}" class="user-image" alt="User Image">
        <span class="hidden-xs">{{ Auth::user()->pessoa->pes_nome }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li class="user-header">
            <img src="{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0) }}" alt="User Image" class="img-circle">
            <p>{{ Auth::user()->pessoa->pes_nome }}</p>
        </li>
        <li class="user-footer">
            <div class="d-flex justify-content-between">
                <div>
                    <a href="{{ route('seguranca.profile.index') }}" class="btn btn-default btn-flat">
                        <i class="fa fa-edit"></i> Perfil
                    </a>
                </div>
                <div>
                    <a href="{{ route('auth.logout') }}" class="btn btn-default btn-flat">
                        <i class="fa fa-sign-out"></i> Sair
                    </a>
                </div>
            </div>
        </li>