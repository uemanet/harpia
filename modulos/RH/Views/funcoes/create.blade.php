@extends('layouts.modulos.rh')

@section('title')
    Funções
@stop

@section('subtitle')
    Cadastro de funções
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de funções</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'rh.funcoes.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('RH::funcoes.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
