@extends('layouts.modulos.academico')

@section('title')
    Carteiras de Estudante
@stop

@section('subtitle')
    Editar Lista de Carteiras de Estudantes
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Lista de Carteiras de Estudante</h3>
        </div>
        <div class="box-body">
            {!! Form::model($lista, ['route' => ['academico.carteirasestudantis.edit', $lista->lst_id], "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Academico::carteirasestudantis.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop