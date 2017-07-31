@extends('layouts.modulos.seguranca')

@section('title')
    Editar Permissão
@stop

@section('subtitle')
    {{$permissao->prm_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Editar Permissão</h3>
        </div>
        <div class="box-body">
            {!! Form::model($permissao, ["route" => ['seguranca.permissoes.edit', $permissao->prm_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::permissoes.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop