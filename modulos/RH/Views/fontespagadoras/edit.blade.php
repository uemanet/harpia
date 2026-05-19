@extends('layouts.modulos.default')

@section('title')
    Fontes Pagadoras
@stop

@section('subtitle')
    Edição de Fonte Pagadora
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de edição de fonte pagadora</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.fontespagadoras.edit', [$fontepagadora->fpg_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $fontepagadora - inputs devem usar old('campo', $fontepagadora->campo) --}}
                @include('RH::fontespagadoras.includes.formulario')
            </form>

        </div>
    </div>
@stop
