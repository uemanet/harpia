@extends('layouts.modulos.default')

@section('title')
    Setores
@stop

@section('subtitle')
    Edição de setor
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de setor</h3>
        </div>
        <form action="{{ route('rh.setores.edit', [$setor->set_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('RH::setores.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
