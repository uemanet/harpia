@extends('layouts.modulos.default')

@section('title')
    Ofertas de Turmas
@stop

@section('subtitle')
    Cadastro de oferta de turmas
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de turmas</h3>
        </div>
        <form action="{{ route('academico.ofertascursos.turmas.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Academico::turmas.includes.formulario_create')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
