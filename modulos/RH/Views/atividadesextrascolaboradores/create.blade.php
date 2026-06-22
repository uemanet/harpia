@extends('layouts.modulos.default')

@section('breadcrumbs')
    {{ Breadcrumbs::render('rh.colaboradores.atividadesextrascolaboradores.create', $colaborador->col_id) }}
@endsection

@section('title')
    Atividades Extras
@stop

@section('subtitle')
    Cadastro Atividades Extras
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de Atividade Extra</h3>
        </div>
        <form action="{{ route('rh.colaboradores.atividadesextrascolaboradores.create', [$colaborador->col_id]) }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('RH::atividadesextrascolaboradores.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
