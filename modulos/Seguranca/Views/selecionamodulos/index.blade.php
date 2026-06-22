@extends('layouts.clean')

@section('title','Harpia - Selecionar Módulo')

@section('content')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="callout callout-info">
                        <h3 class="timeline-header">Bem-Vindo ao <a href="{{ route('index') }}" class="link-primary" style="text-decoration: none;">Harpia</a></h3>
                        <p>Escolha um dos módulos para começar!</p>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 my-2">

                        @if($modulos->count())
                            @foreach($modulos as $modulo)
                                <div class="col">
                                    <a
                                            href="{{ route($modulo->mod_slug.'.index.index') }}"
                                            style="text-decoration: none"
                                    >
                                        <div class="small-box text-{{$modulo->mod_classes}}">
                                            <div class="inner">
                                                <h3>{{$modulo->mod_nome}}</h3>

                                                <p>{{$modulo->mod_descricao}}&nbsp;</p>
                                            </div>
                                            <i class="small-box-icon {{$modulo->mod_icone}}"></i>
                                            <span
                                                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                                            >
                                                Acessar <i class="bi bi-link-45deg"></i>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <h3 style="color:#c3c3c3;padding-top:170px;margin-top:0px" class="text-center">Nenhum módulo disponível para seu usuário</h3>
                        @endif
                    </div>
            </div>
        </section>
    </div>
@stop