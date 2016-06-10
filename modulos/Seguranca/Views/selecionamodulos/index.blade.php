@extends('layouts.clean')

@section('content')
    <div class="wrapper">
    <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="{{url('/')}}" style="padding-top:2px" class="navbar-brand">
            <img src="{{url('/')}}/img/logo.png" style="height:47px" />
          </a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
              <a href="{{url('/')}}/logout">
                <span class="hidden-xs">Sair</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
<div class="content-wrapper" style="min-height: 418px;padding-top:10px">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-body" style="min-height:702px">
                        <div class="row-fluid">
                            <div class="box box-widget widget-user">
                                <div class="widget-user-header bg-aqua-active">
                                    <h3 class="widget-user-username">{{$infoUser['pes_nome']}}</h3>
                                    <h5 class="widget-user-desc">{{$infoUser['pes_email']}}</h5>
                                </div>
                                <div class="widget-user-image">
                                  <img class="img-circle" src="{{url('/')}}/img/user.jpg" alt="User Avatar">
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-sm-4 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header">{{$infoUser['usr_usuario']}}</h5>
                                                <span class="description-text">USUÁRIO</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="description-block">
                                                <h5 class="description-header">{{count($modulos)}}</h5>
                                                <span class="description-text">MÓDULO(S)</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border-right">
                                            <div class="description-block">
                                                <a class="text-light-blue" href="{{url('/')}}/seguranca/profile" >
                                                    <i class="fa fa-unlock-alt"></i> Alterar perfil
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                              @if(count($modulos))
                                @foreach($modulos as $modulo)
                                  <div class="col-lg-12 col-xs-12">
                                      <div class="small-box {{$modulo['mod_class']}}">
                                          <div class="inner">
                                              <h3 style="margin-bottom:0px;font-weight:200;">{{$modulo['mod_nome']}}</h3>
                                              <p>{{$modulo['mod_descricao']}}</p>
                                          </div>
                                          <div class="icon">
                                              <i class="{{$modulo['mod_icone']}}"></i>
                                          </div>
                                          <a href="{{url('/').'/'.$modulo['mod_rota']}}/index" style="padding-top:15px;padding-bottom:15px" class="small-box-footer">
                                              Acessar <i class="fa fa-arrow-circle-right"></i>
                                          </a>
                                      </div>
                                  </div>
                                @endforeach
                              @else
                                <h3 style="color:#c3c3c3;padding-top:170px;margin-top:0px" class="text-center">Nenhum módulo disponível para seu usuário</h3>
                              @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-body" style="min-height:702px">
                        <ul class="timeline">
                            <li>
                              <i class="fa fa-envelope bg-blue"></i>

                              <div class="timeline-item">
                                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                                <div class="timeline-body">
                                  Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                  weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                  weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                  weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                </div>
                              </div>
                            </li>
                            <li>
                              <i class="fa fa-user bg-aqua"></i>
                              <div class="timeline-item">
                                <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request</h3>
                              </div>
                            </li>
                            <li>
                              <i class="fa fa-comments bg-yellow"></i>
                              <div class="timeline-item">
                                <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>
                                <div class="timeline-body">
                                  Take me to your leader!
                                  Switzerland is small and neutral!
                                  We are more like Germany, ambitious and misunderstood!
                                  We are more like Germany, ambitious and misunderstood!
                                  We are more like Germany, ambitious and misunderstood!
                                </div>
                              </div>
                            </li>
                            <li>
                              <i class="fa fa-book bg-purple"></i>
                              <div class="timeline-item">
                                <h3 class="timeline-header"><a href="#">Manuais</a> disponíveis para download</h3>
                                <div class="timeline-body">
                                  <img src="http://placehold.it/150x100" alt="..." class="margin">
                                  <img src="http://placehold.it/150x100" alt="..." class="margin">
                                  <img src="http://placehold.it/150x100" alt="..." class="margin">
                                  <img src="http://placehold.it/150x100" alt="..." class="margin">
                                </div>
                              </div>
                            </li>
                          </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop