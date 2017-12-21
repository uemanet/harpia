@extends('layouts.modulos.seguranca')

@section('title')
    Editar Item de Menu
@stop

@section('subtitle')
    {{$itemMenu->mit_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Item de Menu</h3>
        </div>
        <div class="box-body">
            {!! Form::model($itemMenu, ["route" => ['seguranca.menuitens.edit', $itemMenu->mit_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::menuitens.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop