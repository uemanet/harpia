@extends('layouts.modulos.academico')

@section('title')
    Tutores
@stop

@section('subtitle')
    Cadastro de tutores
@stop

@section('content')
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Cadastro de Tutores</h3>
            <span class="label label-warning pull-right">Tutor</span>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.tutores.create') }}" method="POST" id="form" role="form">
    @csrf

            <h4 class="box-title">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])

            <div class="row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Salvar Tutor</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@stop