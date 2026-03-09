@extends('layouts.modulos.default')

@section('title')
    Itens de Menu
@stop

@section('subtitle')
    Cadastro de itens
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de Cadastro de Itens de Menu</h3>
            </div>
            {!! Form::open(["route" => 'seguranca.menuitens.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            <div class="card-body">
                <div class="row">
                    @include('Seguranca::menuitens.includes.formulario')
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Item</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop