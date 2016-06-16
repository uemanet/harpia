@extends('layouts.modulos.seguranca')

@section('title')
    Módulos
@stop

@section('subtitle')
    Cadastro de módulo
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de módulos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/seguranca/modulos/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::modulos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop