@extends('layouts.modulos.default')

@section('title')
    Salários
@stop

@section('subtitle')
    Alterar Salário
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de Edição de Salário</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.colaboradores.salarioscolaboradores.edit', [$salario->scb_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $salario - inputs devem usar old('campo', $salario->campo) --}}
            @include('RH::salarioscolaboradores.includes.formulario')
            </form>
        </div>
    </div>
@stop