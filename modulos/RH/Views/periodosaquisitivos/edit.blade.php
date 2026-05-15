@extends('layouts.modulos.default')

@section('title')
    Período Aquisitivo
@stop

@section('subtitle')
    Alterar titulação :: {{$periodo_aquisitivo->atc_titulo}}
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Edição de Período Aquisitivo</h3>
        </div>
        <form action="{{ route('rh.colaboradores.periodosaquisitivos.edit', [$periodo_aquisitivo->paq_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                <input type="hidden" name="atc_col_id" value="{{ $periodo_aquisitivo->colaborador->col_id }}" >
                @include('RH::periodosaquisitivos.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop