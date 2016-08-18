@extends('layouts.modulos.seguranca')

@section('title')
    Departamentos
@stop

@section('subtitle')
    Alterar departamento :: {{$departamento->dep_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de departamento</h3>
        </div>
        <div class="box-body">
            {!! Form::model($departamento,["url" => url('/') . "/academico/departamentos/edit/$departamento->dep_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                 @include('Academico::departamentos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop