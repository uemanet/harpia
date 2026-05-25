@extends('layouts.modulos.default')

@section('title')
    Ambientes Virtuais
@stop

@section('subtitle')
    Edição de ambiente virtual
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de ambientes virtuais</h3>
        </div>
        <form action="{{ route('integracao.ambientesvirtuais.edit', [$ambientevirtual->amb_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('Integracao::ambientesvirtuais.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
