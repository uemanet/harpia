@extends('layouts.modulos.seguranca')

@section('title')
    Recursos
@stop

@section('subtitle')
    Alterar recurso :: {{$recurso->rcs_id}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de recurso</h3>
        </div>
        <div class="box-body">
            {!! Form::model($recurso,["url" => url('/') . "/seguranca/recursos/edit/$recurso->rcs_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::recursos.includes.formulario_edit')
            {!! Form::close() !!}
        </div>
    </div>
@stop