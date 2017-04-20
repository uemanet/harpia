@extends('layouts.modulos.seguranca')

@section('title')
    Perfis
@stop

@section('subtitle')
    Alterar perfil :: {{$perfil->prf_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de perfil</h3>
        </div>
        <div class="box-body">
            {!! Form::model($perfil,["route" => ['seguranca.perfis.edit', $perfil->prf_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Seguranca::perfis.includes.formulario_edit')
            {!! Form::close() !!}
        </div>
    </div>
@stop