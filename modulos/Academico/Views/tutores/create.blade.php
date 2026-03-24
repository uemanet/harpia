@extends('layouts.modulos.default')

@section('title')
    Tutores
@stop

@section('subtitle')
    Cadastro de tutores
@stop

@section('content')
    <div class="card card-warning">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Cadastro de Tutores</h3>
            <span class="label label-warning pull-right">Tutor</span>
        </div>
        <form action="{{ route('academico.tutores.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop