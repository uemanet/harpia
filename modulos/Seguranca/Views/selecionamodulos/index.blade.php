@extends('layouts.clean')

@section('title','Harpia - Selecionar Módulo')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body" style="min-height:702px">
                    <div class="row-fluid">
                        <div class="row">
                          @if($modulos->count())
                            @foreach($modulos as $modulo)
                              <div class="col-lg-12 col-xs-12">
                                  <div class="small-box {{$modulo->mod_classes}}">
                                      <div class="inner">
                                          <h3 style="margin-bottom:0px;font-weight:200;">{{$modulo->mod_nome}}</h3>
                                          <p>{{$modulo->mod_descricao}}</p>
                                      </div>
                                      <div class="icon">
                                          <i class="{{$modulo->mod_icone}}"></i>
                                      </div>
                                      <a href="{{ route($modulo->mod_slug.'.index.index') }}" style="padding-top:15px;padding-bottom:15px" class="small-box-footer">
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
                          <i class="fa fa-arrow-right bg-blue"></i>

                          <div class="timeline-item">
                            <h3 class="timeline-header">Bem-Vindo ao <a href="{{ route('index') }}">Harpia</a></h3>

                            <div class="timeline-body">
                              Escolha um dos módulos para começar!
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