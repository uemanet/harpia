@extends('layouts.modulos.default')

@section('title')
    Polos
@stop

@section('subtitle')
    Cadastro de polo
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header">
            <h3 class="card-title">Formulário de cadastro de polos</h3>
        </div>
        <form action="{{ route('academico.polos.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Academico::polos.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
