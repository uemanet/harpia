@extends('layouts.modulos.academico')

@section('title')
    Chamadas
@stop

@section('subtitle')
    Edição de chamada
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de chamadas</h3>
        </div>
        <div class="box-body">
            {!! Form::model($chamada, ["route" => ['matriculas.chamadas.edit',$chamada->id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Matriculas::chamadas.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
