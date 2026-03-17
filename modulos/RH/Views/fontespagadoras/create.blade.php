@extends('layouts.modulos.default')

@section('title')
    Fontes Pagadoras
@stop

@section('subtitle')
    Cadastro de Fontes Pagadoras
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de fonte pagadora</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('rh.fontespagadoras.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('RH::fontespagadoras.includes.formulario')
            </form>
        </div>
    </div>
@stop
