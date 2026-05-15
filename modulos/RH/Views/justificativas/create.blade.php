@extends('layouts.modulos.default')

@section('breadcrumbs')
    {{ Breadcrumbs::render('rh.horastrabalhadas.justificativas.create', $horaTrabalhada->htr_id) }}
@endsection

@section('title')
    Justificativas
@stop

@section('subtitle')
    Cadastro de Justificativa
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de justificativas</h3>
        </div>
        <form action="{{ route('rh.horastrabalhadas.justificativas.create') }}" method="POST" id="form" role="form" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                @include('RH::justificativas.includes.formulario_create')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
