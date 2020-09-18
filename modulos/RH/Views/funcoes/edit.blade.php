@extends('layouts.modulos.rh')

@section('title')
    Funções
@stop

@section('subtitle')
    Edição de função
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de função</h3>
        </div>
        <div class="box-body">
            {!! Form::model($funcao, ["route" => ['rh.funcoes.edit',$funcao->fun_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('RH::funcoes.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
