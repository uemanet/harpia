@extends('layouts.modulos.default')

@section('title')
    Vínculos
@stop

@section('subtitle')
    Edição de vínculo
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de vínculo</h3>
        </div>
        <form action="{{ route('rh.vinculos.edit', [$vinculo->vin_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('RH::vinculos.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
