@extends('layouts.modulos.default')

@section('title')
    Setores
@stop

@section('subtitle')
    Cadastro de setores
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de setores</h3>
        </div>
        <form action="{{ route('rh.setores.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('RH::setores.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
