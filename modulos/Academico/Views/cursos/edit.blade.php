@extends('layouts.modulos.default')

@section('title')
    Curso
@stop

@section('subtitle')
    Alterar curso :: {{$curso->crs_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de curso</h3>
        </div>
        <form action="{{ route('academico.cursos.edit', [$curso->crs_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('Academico::cursos.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
