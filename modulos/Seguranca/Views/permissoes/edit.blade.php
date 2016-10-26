@extends('layouts.modulos.seguranca')

@section('title')
    Permissões
@stop

@section('subtitle')
    Alterar permissão :: {{$permissao->prm_id}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de permissão</h3>
        </div>
        <div class="box-body">
            {!! Form::model($permissao, ["url" => url('/') . "/seguranca/permissoes/edit/$permissao->prm_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::permissoes.includes.formulario_edit')
            {!! Form::close() !!}
        </div>
    </div>
@stop