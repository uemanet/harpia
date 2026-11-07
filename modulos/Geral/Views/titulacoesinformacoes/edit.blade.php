@extends('layouts.modulos.default')

@section('title')
    Titulacoes
@stop

@section('subtitle')
    Alterar titulação :: {{$titulacaoInfo->tin_titulo}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de Edição de Titulação</h3>
            </div>
            <form action="{{ route('geral.pessoas.titulacoesinformacoes.edit', [$titulacaoInfo->tin_id]) }}" method="POST" id="form" role="form">
                @csrf
                @method('PUT')
                <input type="hidden" name="tin_pes_id" value="{{ $pessoa }}" >
                <div class="card-body">
                    <div class="row">
                        @include('Geral::titulacoesinformacoes.includes.formulario')
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop