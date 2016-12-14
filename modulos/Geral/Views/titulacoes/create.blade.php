@extends('layouts.modulos.academico')

@section('title')
    Titulações
@stop

@section('subtitle')
    Cadastro de titulações
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de titulações</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/geral/titulacoes/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('Geral::titulacoes.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
