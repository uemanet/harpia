@extends('layouts.modulos.default')

@section('title')
    Ofertas de Cursos
@stop

@section('subtitle')
    Edição de oferta de curso
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de ofertas de cursos</h3>
        </div>
        <form action="{{ route('academico.ofertascursos.edit', ['id' => $ofertaCurso->ofc_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('Academico::ofertascursos.includes.formulario_edit')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop