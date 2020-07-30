@extends('layouts.modulos.rh')

@section('title')
    Áreas de Conhecimento
@stop

@section('subtitle')
    Edição de área de conhecimento
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de áreas de conhecimento</h3>
        </div>
        <div class="box-body">
            {!! Form::model($areaConhecimento, ["route" => ['rh.areasconhecimentos.edit',$areaConhecimento->arc_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('RH::areasconhecimentos.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
