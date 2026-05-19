@extends('layouts.modulos.default')

@section('title')
    Funções
@stop

@section('subtitle')
    Cadastro de funções
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de cadastro de funções</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.funcoes.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('RH::funcoes.includes.formulario')
            </form>
        </div>
    </div>
@stop
