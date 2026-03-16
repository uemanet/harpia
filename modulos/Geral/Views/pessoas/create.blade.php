@extends('layouts.modulos.default')

@section('title')
    Pessoas
@stop

@section('subtitle')
    Cadastro de pessoas
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de cadastro de pessoas</h3>
            </div>
            {!! Form::open(["route" => 'geral.pessoas.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            <div class="card-body">
                <div class="row">
                    @include('Geral::pessoas.includes.formulario')
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop