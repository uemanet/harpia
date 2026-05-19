@extends('layouts.modulos.default')

@section('title')
    Colaboradores
@stop

@section('subtitle')
    Cadastro de colaboradores
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de Cadastro de Colaboradores</h3>
                <span class="badge bg-success float-end">Colaborador</span>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.colaboradores.create') }}" method="POST" id="form" role="form" enctype="multipart/form-data">
    @csrf

            <h4 class="card-title m-0">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])
            <h4 class="card-title m-0">
                Dados do colaborador
            </h4>
            @include('RH::colaboradores.includes.formulario')

            <div class="row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary float-end">Salvar Colaborador</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@stop
