@extends('layouts.modulos.default')

@section('title')
    Fontes Pagadoras
@stop

@section('subtitle')
    Edição de Fonte Pagadora
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de fonte pagadora</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('rh.fontespagadoras.edit', [$fontepagadora->fpg_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $fontepagadora - inputs devem usar old('campo', $fontepagadora->campo) --}}
                @include('RH::fontespagadoras.includes.formulario')
            </form>

        </div>
    </div>
@stop
