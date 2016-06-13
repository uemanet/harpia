@extends('layouts.interno')

@section('title')
    Categorias de recursos
@stop

@section('subtitle')
    Alterar categoria :: {{$categoria->ctr_id}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de categoria</h3>
        </div>
        <div class="box-body">
            {!! Form::model($categoria,["url" => url('/') . "/seguranca/categoriasrecursos/edit/$categoria->ctr_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::categoriasrecursos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop