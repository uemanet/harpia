@extends('layouts.modulos.rh')

@section('title')
    Bancos
@stop

@section('subtitle')
    Edição de banco
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de bancos</h3>
        </div>
        <div class="box-body">
            {!! Form::model($banco, ["route" => ['rh.bancos.edit',$banco->ban_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('RH::bancos.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
