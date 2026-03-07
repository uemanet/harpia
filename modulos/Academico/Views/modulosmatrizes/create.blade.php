@extends('layouts.modulos.academico')

@section('title')
    Módulo
@stop

@section('subtitle')
    Cadastro de módulos
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de módulos</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.cursos.matrizescurriculares.modulosmatrizes.create') }}" method="POST" id="form" role="form" enctype="multipart/form-data">
    @csrf
                 @include('Academico::modulosmatrizes.includes.formulario')
            </form>
        </div>
    </div>
@stop
