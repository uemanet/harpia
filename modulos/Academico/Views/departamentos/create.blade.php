@extends('layouts.modulos.academico')

@section('title')
    Departamentos
@stop

@section('subtitle')
    Cadastro de departamento
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de departamentos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/academico/departamentos/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('Academico::departamentos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop