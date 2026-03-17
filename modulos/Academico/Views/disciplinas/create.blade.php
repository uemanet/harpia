@extends('layouts.modulos.default')

@section('title')
    Disciplinas
@stop

@section('subtitle')
    Cadastro de disciplina
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de disciplinas</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.disciplinas.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('Academico::disciplinas.includes.formulario')
            </form>
        </div>
    </div>
@stop
