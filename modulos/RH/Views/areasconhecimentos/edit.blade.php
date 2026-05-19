@extends('layouts.modulos.default')

@section('title')
    Áreas de Conhecimento
@stop

@section('subtitle')
    Edição de área de conhecimento
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de edição de áreas de conhecimento</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.areasconhecimentos.edit', [$areaConhecimento->arc_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $areaConhecimento - inputs devem usar old('campo', $areaConhecimento->campo) --}}
                @include('RH::areasconhecimentos.includes.formulario')
            </form>

        </div>
    </div>
@stop
