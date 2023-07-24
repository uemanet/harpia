@extends('layouts.modulos.academico')

@section('title')
    Importação
@stop

@section('subtitle')
    Importação de Pessoas
@stop

@section('content')
    @section('content')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Formulário de importação de Pessoas</h3>
            </div>
            <div class="box-body">
                {!! Form::open(["route" => ['academico.importacoes.importar'], "method" => "POST", "id" => "form", "role" => "form", "enctype" => "multipart/form-data"]) !!}
                @include('Academico::importacao.includes.formulario')
                {!! Form::close() !!}
            </div>
        </div>
    @stop

@stop
