@extends('layouts.modulos.seguranca')

@section('title')
    Módulos
@stop

@section('subtitle')
    Alterar módulo :: {{$modulo->mod_id}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de módulo</h3>
        </div>
        <div class="box-body">
            {!! Form::model($modulo,["url" => url('/') . "/seguranca/modulos/edit/$modulo->mod_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::modulos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop