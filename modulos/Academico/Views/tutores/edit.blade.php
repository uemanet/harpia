@extends('layouts.modulos.default')

@section('title')
    Tutores
@stop

@section('subtitle')
    Alterar tutor :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Edição de Tutor</h3>
        </div>
        <form action="{{ route('academico.tutores.edit', [$pessoa->pes_id]) }}" method="POST" id="form" role="form">
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