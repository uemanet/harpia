@extends('layouts.modulos.default')

@section('title')
    Períodos Letivos
@stop

@section('subtitle')
    Cadastro de período letivo
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title">Formulário de cadastro de períodos letivos</h3>
            </div>
            <form action="{{ route('academico.periodosletivos.create') }}" method="POST" id="form" role="form">
                @csrf
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
