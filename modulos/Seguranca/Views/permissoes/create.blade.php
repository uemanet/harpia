@extends('layouts.modulos.seguranca')

@section('title')
    Permissões
@stop

@section('subtitle')
    Cadastro de permissão
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de permissões</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/seguranca/permissoes/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::permissoes.includes.formulario_create')
            {!! Form::close() !!}
        </div>
    </div>
@stop