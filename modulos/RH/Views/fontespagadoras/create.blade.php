@extends('layouts.modulos.rh')

@section('title')
    Fontes Pagadoras
@stop

@section('subtitle')
    Cadastro de Fontes Pagadoras
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de fonte pagadora</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'rh.fontespagadoras.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('RH::fontespagadoras.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
