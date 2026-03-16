@extends('layouts.modulos.default')

@section('title')
    Usuários
@stop

@section('subtitle')
    Cadastro de usuários
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de Cadastro de Usuários</h3>
            </div>
            {!! Form::open(["route" => 'seguranca.usuarios.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            <div class="card-body">
                <h4 class="box-title">
                    Dados de Usuário
                </h4>
                @include('Seguranca::usuarios.includes.formulario')

                <hr>
                <h4 class="box-title">
                    Dados de Pessoa
                </h4>
                @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])
            </div>
            <div class="card-footer">
                {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop