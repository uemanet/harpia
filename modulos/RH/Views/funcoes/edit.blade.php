@extends('layouts.modulos.default')

@section('title')
    Funções
@stop

@section('subtitle')
    Edição de função
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de edição de função</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.funcoes.edit', [$funcao->fun_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $funcao - inputs devem usar old('campo', $funcao->campo) --}}
                @include('RH::funcoes.includes.formulario')
            </form>

        </div>
    </div>
@stop
