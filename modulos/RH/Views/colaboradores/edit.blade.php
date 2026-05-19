@extends('layouts.modulos.default')

@section('title')
    Colaboradors
@stop

@section('subtitle')
    Alterar Colaborador :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de Edição de Colaborador</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.colaboradores.edit', [$colaborador->col_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $pessoa - inputs devem usar old('campo', $pessoa->campo) --}}

            <h4 class="card-title m-0">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])

            <h4 class="card-title m-0">
                Dados do colaborador
            </h4>
            @include('RH::colaboradores.includes.formulario_edit', ['colaborador' => $colaborador])

            <div class="row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary float-end">Salvar dados</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@stop