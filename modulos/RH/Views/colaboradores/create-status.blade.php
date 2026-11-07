@extends('layouts.modulos.default')

@section('title')
    Matrícula de Colaborador
@stop

@section('subtitle')
    Cadastro de Matrícula
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de matrícula</h3>
        </div>
        <form action="{{ route('rh.colaboradores.matricula.create', ['id' => $colaborador->col_id]) }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('RH::colaboradores.includes.formulario_matricula')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
