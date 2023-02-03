@extends('layouts.modulos.academico')

@section('title')
    Instituições
@stop

@section('subtitle')
    Cadastro de Instituições
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de instituições</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'academico.instituicoes.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('Academico::instituicoes.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
