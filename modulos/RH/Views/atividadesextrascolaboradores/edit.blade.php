@extends('layouts.modulos.seguranca')

@section('title')
    Atividades Extras
@stop

@section('subtitle')
    Alterar titulação :: {{$atividade_extra->atc_titulo}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Atividade Extra</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('rh.colaboradores.atividadesextrascolaboradores.edit', [$atividade_extra->atc_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $atividade_extra - inputs devem usar old('campo', $atividade_extra->campo) --}}
            <input type="hidden" name="atc_col_id" value="{{ $atividade_extra->colaborador->col_id }}" >
            @include('RH::atividadesextrascolaboradores.includes.formulario')
            </form>
        </div>
    </div>
@stop