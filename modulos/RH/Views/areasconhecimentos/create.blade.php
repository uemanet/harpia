@extends('layouts.modulos.rh')

@section('title')
    Áreas de Conhecimento
@stop

@section('subtitle')
    Cadastro de áreas de Conhecimento
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de áreas de conhecimento</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'rh.areasconhecimentos.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('RH::areasconhecimentos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
