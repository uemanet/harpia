@extends('layouts.modulos.default')

@section('title')
    Ofertas de Cursos
@stop

@section('subtitle')
    Cadastro de oferta de curso
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de ofertas de cursos</h3>
        </div>
        <form action="{{ route('academico.ofertascursos.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Academico::ofertascursos.includes.formulario_create')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
