@extends('layouts.modulos.default')

@section('title')
    Cursos
@stop

@section('subtitle')
    Cadastro de curso
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de cursos</h3>
        </div>
        <form action="{{ route('academico.cursos.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Academico::cursos.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop