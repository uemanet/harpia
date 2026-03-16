@extends('layouts.modulos.default')

@section('title')
    Editar Permissão
@stop

@section('subtitle')
    {{$permissao->prm_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Editar Permissão</h3>
            </div>
            {!! Form::model($permissao, ["route" => ['seguranca.permissoes.edit', $permissao->prm_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            <div class="card-body">
                <div class="row">
                    @include('Seguranca::permissoes.includes.formulario')
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop