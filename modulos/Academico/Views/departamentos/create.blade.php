@extends('layouts.modulos.default')

@section('title')
    Departamentos
@stop

@section('subtitle')
    Cadastro de departamento
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de departamentos</h3>
        </div>
        <form action="{{ route('academico.departamentos.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Academico::departamentos.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
