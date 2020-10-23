@extends('layouts.modulos.academico')

@section('title')
    Chamadas
@stop

@section('subtitle')
    Cadastro de chamada
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de chamadas</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'matriculas.chamadas.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('Matriculas::chamadas.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
