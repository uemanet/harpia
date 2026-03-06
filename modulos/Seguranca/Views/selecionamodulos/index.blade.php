@extends('layouts.clean')

@section('title','Harpia - Selecionar Módulo')

@section('content')
    <div class="content-wrapper">

        <div class="content-header p-3">
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
                <div class="row w-100">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 w-100">

                        @if($modulos->count())
                            @foreach($modulos as $modulo)
                                <div class="col">
                                    <div class="small-box text-{{$modulo->mod_classes}}">
                                        <div class="inner">
                                            <h3>{{$modulo->mod_nome}}</h3>

                                            <p>{{$modulo->mod_descricao}}&nbsp;</p>
                                        </div>
                                        <i class="small-box-icon {{$modulo->mod_icone}}"></i>
    {{--                                    <svg--}}
    {{--                                            class="small-box-icon"--}}
    {{--                                            fill="currentColor"--}}
    {{--                                            viewBox="0 0 24 24"--}}
    {{--                                            xmlns="http://www.w3.org/2000/svg"--}}
    {{--                                            aria-hidden="true"--}}
    {{--                                    >--}}
    {{--                                        <path--}}
    {{--                                                d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"--}}
    {{--                                        ></path>--}}
    {{--                                    </svg>--}}
                                        <a
                                                href="{{ route($modulo->mod_slug.'.index.index') }}"
                                                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                                        >
                                            Acessar <i class="bi bi-link-45deg"></i>
                                        </a>
                                    </div>
                                </div>
{{--                                <div class="flex-item p-3" style="flex: 1 1 33%;">--}}
{{--                                    <div class="small-box {{$modulo->mod_classes}}">--}}
{{--                                        <div class="inner">--}}
{{--                                            <h3>{{$modulo->mod_nome}}</h3>--}}
{{--                                            <p>{{$modulo->mod_descricao}} &nbsp;</p>--}}
{{--                                        </div>--}}
{{--                                        <div class="icon">--}}
{{--                                            <i class="{{$modulo->mod_icone}}"></i>--}}
{{--                                        </div>--}}
{{--                                        <a href="{{ route($modulo->mod_slug.'.index.index') }}" class="small-box-footer">Acessar <i class="fas fa-arrow-circle-right"></i></a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
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