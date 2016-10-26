@extends('layouts.modulos.seguranca')

@section('title')
    Recursos
@stop

@section('subtitle')
    Cadastro de recurso
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de recursos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/seguranca/recursos/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::recursos.includes.formulario_create')
            {!! Form::close() !!}
        </div>
    </div>
@stop