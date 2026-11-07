@extends('layouts.modulos.default')

@section('title')
    Ambientes Virtuais
@stop

@section('subtitle')
    Cadastro de ambiente virtual
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de ambientes virtuais</h3>
        </div>
        <form action="{{ route('integracao.ambientesvirtuais.create') }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('Integracao::ambientesvirtuais.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
