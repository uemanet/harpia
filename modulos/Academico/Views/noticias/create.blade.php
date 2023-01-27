@extends('layouts.modulos.academico')

@section('title')
    Noticias
@stop

@section('subtitle')
    Cadastro de notícia
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de notícias</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'academico.noticias.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('Academico::noticias.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
