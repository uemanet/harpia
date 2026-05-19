@extends('layouts.modulos.default')

@section('title')
    Fontes Pagadoras
@stop

@section('subtitle')
    Cadastro de Fontes Pagadoras
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de cadastro de fonte pagadora</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.fontespagadoras.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('RH::fontespagadoras.includes.formulario')
            </form>
        </div>
    </div>
@stop
