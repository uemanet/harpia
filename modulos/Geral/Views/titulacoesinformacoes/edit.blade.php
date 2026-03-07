@extends('layouts.modulos.seguranca')

@section('title')
    Titulacoes
@stop

@section('subtitle')
    Alterar titulação :: {{$titulacaoInfo->tin_titulo}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Titulação</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('geral.pessoas.titulacoesinformacoes.edit', [$titulacaoInfo->tin_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $titulacaoInfo - inputs devem usar old('campo', $titulacaoInfo->campo) --}}
            <input type="hidden" name="tin_pes_id" value="{{ $pessoa }}" >
            @include('Geral::titulacoesinformacoes.includes.formulario')
            </form>
        </div>
    </div>
@stop