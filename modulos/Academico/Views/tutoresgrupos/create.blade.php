@extends('layouts.modulos.academico')

@section('title')
    Tutores do grupo
@stop

@section('subtitle')
    Vínculo de tutores
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de vínculo de tutores</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/academico/turmas/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Academico::turmas.includes.formulario_create')
            {!! Form::close() !!}
        </div>
    </div>
@stop
