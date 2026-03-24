@extends('layouts.modulos.default')

@section('title')
    Turmas
@stop

@section('subtitle')
    Alterar turma :: {{$turma->trm_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de turma</h3>
        </div>
        <form action="{{ route('academico.ofertascursos.turmas.edit', [$turma->trm_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                 @include('Academico::turmas.includes.formulario_edit')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
