@extends('layouts.modulos.rh')

@section('title')
    Setores
@stop

@section('subtitle')
    Edição de setor
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de setor</h3>
        </div>
        <div class="box-body">
            {!! Form::model($setor, ["route" => ['rh.setores.edit',$setor->set_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('RH::setores.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
