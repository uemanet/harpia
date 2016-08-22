@extends('layouts.modulos.academico')

@section('title')
    Períodos Letivos
@stop

@section('subtitle')
    Cadastro de período letivo
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de períodos letivos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/academico/periodosletivos/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
              @include('Academico::periodosletivos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop