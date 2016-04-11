@extends('layouts.interno')

@section('titulo')
    <h2>Cadastrar Recurso</h2>
@stop

@section('content')
    <div class="panel panel-default ">
        <div class="panel-body">
            <div class="ibox float-e-margins wrapper wrapper-content">
                <div class="ibox-title">
                    <h5>Formulário de Cadastro de Recurso</h5>
                </div>
                <div class="ibox-content">
                    {!! Form::open(["url" => "/security/recursos/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
                        @include('security.recursos.includes.form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop