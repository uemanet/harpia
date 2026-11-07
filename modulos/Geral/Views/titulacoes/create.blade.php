@extends('layouts.modulos.default')

@section('title')
    Titulações
@stop

@section('subtitle')
    Cadastro de titulações
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de cadastro de titulações</h3>
            </div>
            <form action="{{ route('geral.titulacoes.create') }}" method="POST" id="form" role="form">
                @csrf
                <div class="card-body">
                    <div class="row">
                        @include('Geral::titulacoes.includes.formulario')
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop
