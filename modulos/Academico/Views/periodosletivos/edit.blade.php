@extends('layouts.modulos.default')

@section('title')
    Períodos Letivos
@stop

@section('subtitle')
    Alterar período letivo :: {{$periodoLetivo->per_id}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title">Formulário de edição de períodos letivos</h3>
            </div>
            <form action="{{ route('academico.periodosletivos.edit', [$periodoLetivo->per_id]) }}" method="POST" id="form" role="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @include('Academico::periodosletivos.includes.formulario')
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop