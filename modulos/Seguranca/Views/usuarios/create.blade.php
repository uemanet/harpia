@extends('layouts.modulos.seguranca')

@section('title')
    Usuários
@stop

@section('subtitle')
    Cadastro de usuários
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de usuários</h3>
        </div>
        <div class="box-body">
                {!! Form::open(["url" => url('/') . "/seguranca/usuarios/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::usuarios.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop