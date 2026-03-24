@extends('layouts.modulos.default')

@section('title')
    Módulos
@stop

@section('subtitle')
    Edição de módulo
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de módulo</h3>
        </div>
        <form action="{{ route('academico.cursos.matrizescurriculares.modulosmatrizes.edit', [$modulo->mdo_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('Academico::modulosmatrizes.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
