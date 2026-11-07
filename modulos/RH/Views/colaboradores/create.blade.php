@extends('layouts.modulos.default')

@section('title')
    Colaboradores
@stop

@section('subtitle')
    Cadastro de colaboradores
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Cadastro de Colaboradores</h3>
            <span class="label label-success pull-right">Colaborador</span>
        </div>
        <form action="{{ url('/') . "/rh/colaboradores/create" }}" method="POST" id="form" role="form" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])
                @include('RH::colaboradores.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
