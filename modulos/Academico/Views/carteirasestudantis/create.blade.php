@extends('layouts.modulos.academico')

@section('title')
    Carteiras de Estudante
@stop

@section('subtitle')
    Cadastro de Lista de Carteiras de Estudantes
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Cadastro de Lista de Carteiras de Estudante</h3>
        </div>
        <div class="box-body">
            {!! Form::open(['route' => 'academico.carteirasestudantis.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Academico::carteirasestudantis.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop