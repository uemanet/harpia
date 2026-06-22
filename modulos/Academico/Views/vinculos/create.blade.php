@extends('layouts.modulos.default')

@section('title')
    Vínculos
@stop

@section('subtitle')
    Adicionar vínculo :: Usuário : <b>{{$user->usr_usuario}}</b>
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de vínculos</h3>
        </div>
        <form action="{{ route('academico.vinculos.create', [$usuario]) }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Academico::vinculos.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/vinculos/create.js')
@stop
