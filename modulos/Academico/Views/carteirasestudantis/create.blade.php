@extends('layouts.modulos.default')

@section('title')
    Carteiras de Estudante
@stop

@section('subtitle')
    Cadastro de Lista de Carteiras de Estudantes
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title">Formulário de Cadastro de Lista de Carteiras de Estudante</h3>
            </div>
            <form action="{{ route('academico.carteirasestudantis.create') }}" method="POST" id="form" role="form">
                @csrf
                <div class="card-body">
                    @include('Academico::carteirasestudantis.includes.formulario')
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop