@extends('layouts.modulos.academico')

@section('title')
    Módulos
@stop

@section('subtitle')
    Edição de módulo
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de módulo</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.cursos.matrizescurriculares.modulosmatrizes.edit', [$modulo->mdo_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $modulo - inputs devem usar old('campo', $modulo->campo) --}}
            @include('Academico::modulosmatrizes.includes.formulario')
            </form>

        </div>
    </div>
@stop
