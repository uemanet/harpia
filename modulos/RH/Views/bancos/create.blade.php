@extends('layouts.modulos.rh')

@section('title')
    Bancos
@stop

@section('subtitle')
    Cadastro de bancos
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de bancos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'rh.bancos.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('RH::bancos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
