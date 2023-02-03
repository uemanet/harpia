@extends('layouts.modulos.academico')

@section('title')
    Instituições
@stop

@section('subtitle')
    Edição de instituições
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de instituições</h3>
        </div>
        <div class="box-body">
            {!! Form::model($instituicao, ["route" => ['academico.instituicoes.edit',$instituicao->itt_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Academico::instituicoes.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
