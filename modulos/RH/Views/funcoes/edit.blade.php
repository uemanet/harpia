@extends('layouts.modulos.rh')

@section('title')
    Funções
@stop

@section('subtitle')
    Edição de função
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de função</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('rh.funcoes.edit', [$funcao->fun_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $funcao - inputs devem usar old('campo', $funcao->campo) --}}
                @include('RH::funcoes.includes.formulario')
            </form>

        </div>
    </div>
@stop
