@extends('layouts.modulos.default')

@section('title')
    Período Aquisitivo
@stop

@section('subtitle')
    Alterar titulação :: {{""}}
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Edição de Férias</h3>
        </div>
        <form action="{{ route('rh.colaboradores.periodosgozo.edit', [$periodoGozo->pgz_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('RH::periodosgozo.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop