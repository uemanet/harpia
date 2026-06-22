@extends('layouts.modulos.default')

@section('title')
    Centro
@stop

@section('subtitle')
    Alterar centro :: {{$centro->cen_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Edição de Centro</h3>
        </div>
        <form action="{{ route('academico.centros.edit', [$centro->cen_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('Academico::centros.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
