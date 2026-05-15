@extends('layouts.modulos.default')

@section('title')
    Salários
@stop

@section('subtitle')
    Cadastro Salários
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de Salário</h3>
        </div>
        <form action="{{ route('rh.colaboradores.salarioscolaboradores.create', [$colaborador->col_id]) }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('RH::salarioscolaboradores.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop