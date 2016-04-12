<header class="topnavbar-wrapper">
   <nav role="navigation" class="navbar topnavbar">
      <div class="navbar-header">
         <a href="/index" class="navbar-brand">
            <div class="brand-logo">
               <img style="height:42px" src="{{ url('/') }}/{{config('system.logo')}}" alt="{{config('system.title')}}" class="img-responsive">
            </div>
            <div class="brand-logo-collapsed">
               <img style="height:42px" src="{{ url('/') }}/{{config('system.logo')}}" alt="{{config('system.title')}}" class="img-responsive">
            </div>
         </a>
      </div>
      <div class="nav-wrapper">
         <ul class="nav navbar-nav">
            <li>
               <a href="#" data-toggle-state="aside-toggled" data-no-persist="true" class="visible-xs sidebar-toggle">
                  <em class="fa fa-navicon"></em>
               </a>
            </li>
         </ul>
         <ul class="nav navbar-nav navbar-right">
            <li class="dropdown dropdown-list">
               <a href="#" data-toggle="dropdown">
                  <em class="fa fa-user"></em>
               </a>
               <ul class="dropdown-menu animated flipInX">
                  <li>
                     <div class="list-group">
                        <a href="{{ url('/') }}/security/usuarios/edit/{{Auth::user()->usr_id}}" class="list-group-item">
                           <div class="media-box">
                              <div class="pull-left">
                                 <em class="fa fa-edit fa-2x text-info"></em>
                              </div>
                              <div class="media-box-body clearfix">
                                 <p class="m0">Editar perfil</p>
                                 <p class="m0 text-muted">
                                    <small>Alterar informações pessoais</small>
                                 </p>
                              </div>
                           </div>
                        </a>
                          <a href="{{ url('/') }}/security/usuarios/editpasswor/d{{Auth::user()->usr_id}}" class="list-group-item">
                           <div class="media-box">
                              <div class="pull-left">
                                 <em class="fa fa-key fa-2x text-info"></em>
                              </div>
                              <div class="media-box-body clearfix">
                                 <p class="m0">Alterar senha</p>
                                 <p class="m0 text-muted">
                                    <small>Alterar senha de acesso</small>
                                 </p>
                              </div>
                           </div>
                        </a>
                     </div>
                  </li>
               </ul>
            </li>
            <li>
               <a href="{{url('/')}}/auth/logout" data-toggle-state="offsidebar-open" data-no-persist="true">
                  <em class="fa fa-power-off"></em>
               </a>
            </li>
         </ul>
      </div>
   </nav>
</header>