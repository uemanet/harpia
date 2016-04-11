@extends('layouts.interno')

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Alterar Usuário</h2>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-default ">
        <div class="panel-body">
            <div class="ibox float-e-margins wrapper wrapper-content">
                <div class="ibox-title">
                    <h5>Formulário de Alteração de Usuário</h5>
                </div>
                <div class="ibox-content">
                    {!! Form::model($usuario,["url" => "/security/usuarios/edit/".$usuario->usr_id, "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                        {!! Form::hidden('usr_id') !!}
                        @include('security.usuarios.includes.form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop