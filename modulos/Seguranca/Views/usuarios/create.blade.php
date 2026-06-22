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
            <form action="{{ route('seguranca.usuarios.create') }}" method="POST" id="form" role="form">
                @csrf
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
                    <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
                </div>
            </form>
        </div>
    </div>
@stop