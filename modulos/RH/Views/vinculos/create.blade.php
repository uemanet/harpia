@extends('layouts.modulos.rh')

@section('title')
    Vínculos
@stop

@section('subtitle')
    Cadastro de vínculos
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de vínculos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'rh.vinculos.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('RH::vinculos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
