<header class="main-header">
   <nav class="navbar navbar-static-top">
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
              </li>
            </ul>
          </li>
        </ul>
      </div>
   </nav>
</header>