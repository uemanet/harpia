@extends('layouts.interno')

@section('titulo')
    <h2>Cadastrar Nova Permissão</h2>
@stop

@section('content')
    <div class="panel panel-default ">
        <div class="panel-body">
            <div class="ibox float-e-margins wrapper wrapper-content">
                <div class="ibox-title">
                    <h5>Formulário de Cadastro de Permissão</h5>
                </div>
                <div class="ibox-content">
                    {!! Form::open(["url" => "/security/permissoes/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
                        @include('security.permissoes.includes.form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop