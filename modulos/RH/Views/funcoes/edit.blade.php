@extends('layouts.modulos.default')

@section('title')
    Funções
@stop

@section('subtitle')
    Edição de função
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de função</h3>
        </div>
        <form action="{{ route('rh.funcoes.edit', [$funcao->fun_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('RH::funcoes.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
