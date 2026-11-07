@extends('layouts.modulos.default')

@section('title')
    Período Laboral
@stop

@section('subtitle')
    Edição de período laboral
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de períodos laborais</h3>
        </div>
        <form action="{{ route('rh.periodoslaborais.edit', [$periodolaboral->pel_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('RH::periodoslaborais.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop