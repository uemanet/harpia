@extends('layouts.interno')

@section('title')
    Perfis
@stop

@section('subtitle')
    Alterar perfil :: {{$perfil->mod_id}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de módulo</h3>
        </div>
        <div class="box-body">
            {!! Form::model($perfil,["url" => url('/') . "/seguranca/perfis/edit/$perfil->prf_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::perfis.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop