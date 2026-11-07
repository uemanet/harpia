@extends('layouts.modulos.default')

@section('title')
    Disciplinas
@stop

@section('subtitle')
    Cadastro de disciplina
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de disciplinas</h3>
        </div>
        <form action="{{ route('academico.disciplinas.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Academico::disciplinas.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
