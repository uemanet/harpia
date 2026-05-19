@extends('layouts.modulos.default')

@section('title')
    Setores
@stop

@section('subtitle')
    Edição de setor
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de edição de setor</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.setores.edit', [$setor->set_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $setor - inputs devem usar old('campo', $setor->campo) --}}
                @include('RH::setores.includes.formulario')
            </form>

        </div>
    </div>
@stop
