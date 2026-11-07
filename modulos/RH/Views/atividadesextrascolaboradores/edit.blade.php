@extends('layouts.modulos.default')

@section('title')
    Atividades Extras
@stop

@section('subtitle')
    Alterar titulação :: {{$atividade_extra->atc_titulo}}
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Edição de Atividade Extra</h3>
        </div>
        <form action="{{ route('rh.colaboradores.atividadesextrascolaboradores.edit', [$atividade_extra->atc_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                <input type="hidden" name="atc_col_id" value="{{ $atividade_extra->colaborador->col_id }}" >
                @include('RH::atividadesextrascolaboradores.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop