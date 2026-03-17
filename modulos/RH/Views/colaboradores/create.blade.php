@extends('layouts.modulos.default')

@section('title')
    Colaboradores
@stop

@section('subtitle')
    Cadastro de colaboradores
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Cadastro de Colaboradores</h3>
                <span class="label label-success pull-right">Colaborador</span>
        </div>
        <div class="box-body">
            <form action="url(" method="POST" id="form" role="form">
    @csrf

            <h4 class="box-title">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])
            <h4 class="box-title">
                Dados do colaborador
            </h4>
            @include('RH::colaboradores.includes.formulario')

            <div class="row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Salvar Colaborador</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@stop