@extends('layouts.modulos.integracao')

@section('title')
    Ambientes Virtuais
@stop

@section('subtitle')
    Cadastro de ambiente virtual
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de ambientes virtuais</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/integracao/ambientesvirtuais/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('Integracao::ambientesvirtuais.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
