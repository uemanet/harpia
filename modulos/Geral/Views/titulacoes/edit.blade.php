@extends('layouts.modulos.default')

@section('title')
    Titulações
@stop

@section('subtitle')
    Edição de titulações
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de edição de titulações</h3>
            </div>
            {!! Form::model($titulacao, ["route" => ['geral.titulacoes.edit',$titulacao->tit_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            <div class="card-body">
                <div class="row">
                    @include('Geral::titulacoes.includes.formulario')
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
