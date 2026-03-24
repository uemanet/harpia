@extends('layouts.modulos.default')

@section('title')
    Alunos
@stop

@section('subtitle')
    Cadastro de alunos
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Cadastro de Alunos</h3>
        </div>
        <form action="{{ route('academico.alunos.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop