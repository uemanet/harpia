@extends('layouts.modulos.seguranca')

@section('title')
    Salários
@stop

@section('subtitle')
    Alterar Salário
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Salário</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('rh.colaboradores.salarioscolaboradores.edit', [$salario->scb_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $salario - inputs devem usar old('campo', $salario->campo) --}}
            @include('RH::salarioscolaboradores.includes.formulario')
            </form>
        </div>
    </div>
@stop