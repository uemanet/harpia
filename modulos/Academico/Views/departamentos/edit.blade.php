@extends('layouts.modulos.default')

@section('title')
    Departamentos
@stop

@section('subtitle')
    Alterar departamento :: {{$departamento->dep_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de departamento</h3>
        </div>
        <form action="{{ route('academico.departamentos.edit', [$departamento->dep_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                 @include('Academico::departamentos.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
