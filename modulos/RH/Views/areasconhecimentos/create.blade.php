@extends('layouts.modulos.default')

@section('title')
    Áreas de Conhecimento
@stop

@section('subtitle')
    Cadastro de áreas de Conhecimento
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de áreas de conhecimento</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('rh.areasconhecimentos.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('RH::areasconhecimentos.includes.formulario')
            </form>
        </div>
    </div>
@stop
