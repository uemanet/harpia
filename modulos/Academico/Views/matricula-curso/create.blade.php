@extends('layouts.modulos.default')

@section('title')
    Nova Matricula
@stop

@section('subtitle')
    Aluno: {{$aluno->pessoa->pes_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Matrícula</h3>
        </div>
        <form action="{{ route('academico.matricularalunocurso.create', [$aluno->alu_id]) }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Academico::matricula-curso.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop