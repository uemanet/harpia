@extends('layouts.interno')

@section('title')
    Categorias de recursos
@stop

@section('subtitle')
    Cadastro de categorias
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de categorias</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/seguranca/categoriasrecursos/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::categoriasrecursos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop