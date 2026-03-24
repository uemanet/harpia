@extends('layouts.modulos.default')

@section('title')
    Disciplinas
@stop

@section('subtitle')
    Edição de disciplina
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de disciplinas</h3>
        </div>
        <form action="{{ route('academico.disciplinas.edit', [$disciplina->dis_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('Academico::disciplinas.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
