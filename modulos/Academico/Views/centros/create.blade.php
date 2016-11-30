@extends('layouts.modulos.academico')

@section('title')
    Centros
@stop

@section('subtitle')
    Cadastro de centro
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de centros</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/academico/centros/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('Academico::centros.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop