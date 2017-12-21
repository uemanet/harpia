@extends('layouts.modulos.seguranca')

@section('title')
    Permissoes
@stop

@section('subtitle')
    Cadastro de permissao
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de permissoes</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'seguranca.permissoes.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::permissoes.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop