@extends('layouts.modulos.rh')

@section('title')
    Setores
@stop

@section('subtitle')
    Cadastro de setores
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de setores</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'rh.setores.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('RH::setores.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
