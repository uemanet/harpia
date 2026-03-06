@extends('layouts.modulos.default')

@section('title')
    Itens de Menu
@stop

@section('subtitle')
    Cadastro de itens
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Cadastro de Itens de Menu</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'seguranca.menuitens.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::menuitens.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop