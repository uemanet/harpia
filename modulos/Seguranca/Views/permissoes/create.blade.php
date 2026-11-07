@extends('layouts.modulos.default')

@section('title')
    Permissoes
@stop

@section('subtitle')
    Cadastro de permissao
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de cadastro de permissoes</h3>
            </div>
            <form action="{{ route('seguranca.permissoes.create') }}" method="POST" id="form" role="form">
                @csrf
                <div class="card-body">
                    <div class="row">
                        @include('Seguranca::permissoes.includes.formulario')
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop