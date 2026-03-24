@extends('layouts.modulos.default')

@section('title')
    Professores
@stop

@section('subtitle')
    Cadastro de professores
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Cadastro de Professores</h3>
            <span class="label label-primary pull-right">Professor</span>
        </div>
        <form action="{{ route('academico.professores.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Academico::professores.includes.formulario', ['pessoa' => $pessoa])
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
