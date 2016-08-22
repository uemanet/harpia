@extends('layouts.modulos.academico')

@section('title')
    Períodos Letivos
@stop

@section('subtitle')
    Alterar período letivo :: {{$periodoLetivo->per_id}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de períodos letivos</h3>
        </div>
        <div class="box-body">
            {!! Form::model($periodoLetivo, ["url" => url('/') . "/academico/periodosletivos/edit/$periodoLetivo->per_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Academico::periodosletivos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop