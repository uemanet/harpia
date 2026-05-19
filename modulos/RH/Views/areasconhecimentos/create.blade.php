@extends('layouts.modulos.default')

@section('title')
    Áreas de Conhecimento
@stop

@section('subtitle')
    Cadastro de áreas de Conhecimento
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de cadastro de áreas de conhecimento</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.areasconhecimentos.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('RH::areasconhecimentos.includes.formulario')
            </form>
        </div>
    </div>
@stop
