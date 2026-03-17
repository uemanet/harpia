@extends('layouts.modulos.default')

@section('title')
    Setores
@stop

@section('subtitle')
    Edição de setor
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de setor</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('rh.setores.edit', [$setor->set_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $setor - inputs devem usar old('campo', $setor->campo) --}}
                @include('RH::setores.includes.formulario')
            </form>

        </div>
    </div>
@stop
