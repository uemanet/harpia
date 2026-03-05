@extends('layouts.clean')

@section('title','Harpia - Selecionar Módulo')

@section('content')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="callout callout-info w-100">
                        <h3 class="timeline-header">Bem-Vindo ao <a href="{{ route('index') }}" class="link-primary" style="text-decoration: none;">Harpia</a></h3>
                        <p>Escolha um dos módulos para começar!</p>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="d-flex flex-wrap w-100">

                        @if($modulos->count())
                            @foreach($modulos as $modulo)
                                <div class="flex-item p-3" style="flex: 1 1 33%;">
                                    <div class="small-box {{$modulo->mod_classes}}">
                                        <div class="inner">
                                            <h3>{{$modulo->mod_nome}}</h3>
                                            <p>{{$modulo->mod_descricao}} &nbsp;</p>
                                        </div>
                                        <div class="icon">
                                            <i class="{{$modulo->mod_icone}}"></i>
                                        </div>
                                        <a href="{{ route($modulo->mod_slug.'.index.index') }}" class="small-box-footer">Acessar <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h3 style="color:#c3c3c3;padding-top:170px;margin-top:0px" class="text-center">Nenhum módulo disponível para seu usuário</h3>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop