@extends('layouts.modulos.academico')

@section('title')
    Módulo
@stop

@section('subtitle')
    Cadastro de módulos
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de módulos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/academico/modulosmatrizes/create", "method" => "POST", "id" => "form", "role" => "form", "enctype" => "multipart/form-data"]) !!}
                 @include('Academico::modulosmatrizes.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
