@extends('layouts.modulos.academico')

@section('title')
    Titulações
@stop

@section('subtitle')
    Edição de titulações
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de titulações</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('geral.titulacoes.edit', [$titulacao->tit_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $titulacao - inputs devem usar old('campo', $titulacao->campo) --}}
                @include('Geral::titulacoes.includes.formulario')
            </form>

        </div>
    </div>
@stop
