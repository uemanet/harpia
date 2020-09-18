@extends('layouts.modulos.seguranca')

@section('title')
    Salários
@stop

@section('subtitle')
    Alterar Salário
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Salário</h3>
        </div>
        <div class="box-body">
            {!! Form::model($salario,["route" => ['rh.colaboradores.salarioscolaboradores.edit',$salario->scb_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            @include('RH::salarioscolaboradores.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop