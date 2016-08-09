@extends('layouts.clean')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body" style="min-height:702px">
                    <div class="row-fluid">
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
@stop