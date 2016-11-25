@extends('layouts.modulos.academico')

@section('title')
    Disciplinas
@stop

@section('subtitle')
    Edição de disciplina
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de disciplinas</h3>
        </div>
        <div class="box-body">
            {!! Form::model($disciplina,["url" => url('/') . "/academico/disciplinas/edit/$disciplina->dis_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            @include('Academico::disciplinas.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
