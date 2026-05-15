@extends('layouts.modulos.default')

@section('title')
    Áreas de Conhecimento
@stop

@section('subtitle')
    Cadastro de áreas de Conhecimento
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de áreas de conhecimento</h3>
        </div>
        <form action="{{ route('rh.areasconhecimentos.create') }}" method="POST" id="form" role="form">
        @csrf
            <div class="card-body">
                @include('RH::areasconhecimentos.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
