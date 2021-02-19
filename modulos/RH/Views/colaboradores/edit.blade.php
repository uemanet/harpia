@extends('layouts.modulos.rh')

@section('title')
    Colaboradors
@stop

@section('subtitle')
    Alterar Colaborador :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Colaborador</h3>
        </div>
        <div class="box-body">
            {!! Form::model($pessoa,['route' => ['rh.colaboradores.edit', $colaborador->col_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}

            <h4 class="box-title">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])

            <h4 class="box-title">
                Dados do colaborador
            </h4>
            @include('RH::colaboradores.includes.formulario_edit', ['colaborador' => $colaborador])

            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop