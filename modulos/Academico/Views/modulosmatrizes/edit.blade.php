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
            {!! Form::model($modulo, ["url" => url('/') . "/academico/modulosmatrizes/edit/$modulo->mdo_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            @include('Academico::modulosmatrizes.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
