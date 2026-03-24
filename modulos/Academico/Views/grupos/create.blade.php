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
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de grupos</h3>
        </div>
        <div class="card-body">
            <form action="url(" method="POST" id="form" role="form">
    @csrf
            @include('Academico::grupos.includes.formulario_create')
            </form>
        </div>
    </div>
@stop