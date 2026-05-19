@extends('layouts.modulos.default')

@section('title')
    Atividades Extras
@stop

@section('subtitle')
    Alterar titulação :: {{$atividade_extra->atc_titulo}}
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de Edição de Atividade Extra</h3>
        </div>
        <div class="card-body">
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