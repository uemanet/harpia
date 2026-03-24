@extends('layouts.modulos.default')

@section('title')
    Alunos
@stop

@section('subtitle')
    Alterar Aluno :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Edição de Aluno</h3>
        </div>
        <form action="{{ route('academico.alunos.edit', [$pessoa->pes_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop