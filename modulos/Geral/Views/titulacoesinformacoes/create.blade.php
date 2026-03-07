@extends('layouts.modulos.academico')

@section('title')
    Titulações
@stop

@section('subtitle')
    Cadastro de titulações
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de titulações</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('geral.pessoas.titulacoesinformacoes.create', [$pessoa->pes_id]) }}" method="POST" id="form" role="form">
    @csrf
            @include('Geral::titulacoesinformacoes.includes.formulario')
            </form>
        </div>
    </div>
@stop