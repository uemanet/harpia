@extends('layouts.modulos.default')

@section('title')
    Grupos
@stop

@section('subtitle')
    Cadastro de Grupo
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de grupos</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('/'). "/academico/grupos/create" }}" method="POST" id="form" role="form">
                @csrf
                @include('Academico::grupos.includes.formulario_create')
            </form>
        </div>
    </div>
@stop