@extends('layouts.modulos.default')

@section('title')
    Colaboradors
@stop

@section('subtitle')
    Alterar Colaborador :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Edição de Colaborador</h3>
        </div>
        <form action="{{ route('rh.colaboradores.edit', [$colaborador->col_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])
                @include('RH::colaboradores.includes.formulario_edit', ['colaborador' => $colaborador])
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop