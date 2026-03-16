@extends('layouts.modulos.default')

@section('title')
    Perfis
@stop

@section('subtitle')
    Alterar perfil :: {{$perfil->prf_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de edição de perfil</h3>
            </div>
            {!! Form::model($perfil,["route" => ['seguranca.perfis.edit', $perfil->prf_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            <div class="card-body">
                <div class="row">
                    @include('Seguranca::perfis.includes.formulario_edit')
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop