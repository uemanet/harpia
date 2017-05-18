@extends('layouts.modulos.academico')

@section('title')
    Polos
@stop

@section('subtitle')
    Cadastro de polo
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de polos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'academico.polos.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('Academico::polos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop
