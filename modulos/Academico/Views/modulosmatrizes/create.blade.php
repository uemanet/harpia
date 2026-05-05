@extends('layouts.modulos.default')

@section('title')
    Módulo
@stop

@section('subtitle')
    Cadastro de módulos
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de módulos</h3>
        </div>
        <form action="{{ route('academico.cursos.matrizescurriculares.modulosmatrizes.create') }}" method="POST" id="form" role="form" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                 @include('Academico::modulosmatrizes.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
