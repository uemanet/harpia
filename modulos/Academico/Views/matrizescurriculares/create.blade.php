@extends('layouts.modulos.default')

@section('title')
    Matrizes Curriculares
@stop

@section('subtitle')
    Cadastro de matriz curricular
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de matrizes curriculares</h3>
        </div>
        <form action="{{ route('academico.cursos.matrizescurriculares.create') }}" method="POST" id="form" role="form" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                 @include('Academico::matrizescurriculares.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop