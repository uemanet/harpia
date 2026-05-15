@extends('layouts.modulos.default')

@section('breadcrumbs')
    {{ Breadcrumbs::render('rh.colaboradores.periodosgozo.create', $colaborador->col_id) }}
@endsection

@section('title')
    Férias
@stop

@section('subtitle')
    Cadastro de Férias
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de Férias</h3>
        </div>
        <form action="{{ route('rh.colaboradores.periodosgozo.create', [$colaborador->col_id]) }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('RH::periodosgozo.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop