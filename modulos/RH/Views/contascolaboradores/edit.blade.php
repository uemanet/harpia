@extends('layouts.modulos.default')

@section('title')
    Contas de Colaborador
@stop

@section('subtitle')
    Alterar conta de colaborador :: {{$conta_colaborador->ccb_conta}}
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Edição de Conta de Colaborador</h3>
        </div>
        <form action="{{ route('rh.colaboradores.contascolaboradores.edit', [$conta_colaborador->ccb_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                <input type="hidden" name="ccb_col_id" value="{{ $conta_colaborador->colaborador->col_id }}" >
                @include('RH::contascolaboradores.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop