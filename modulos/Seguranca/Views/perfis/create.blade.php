@extends('layouts.modulos.seguranca')

@section('title')
    Perfis
@stop

@section('subtitle')
    Cadastro de perfil
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de perfis</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'seguranca.perfis.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::perfis.includes.formulario_create')
            {!! Form::close() !!}
        </div>
    </div>
@stop