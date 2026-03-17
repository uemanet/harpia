@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Grupos
@stop

@section('subtitle')
    Cadastro de Grupo
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de grupos</h3>
        </div>
        <div class="box-body">
            <form action="url(" method="POST" id="form" role="form">
    @csrf
            @include('Academico::grupos.includes.formulario_create')
            </form>
        </div>
    </div>
@stop