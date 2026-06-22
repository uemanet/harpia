@extends('layouts.modulos.default')

@section('title')
    Carteiras de Estudante
@stop

@section('subtitle')
    Editar Lista de Carteiras de Estudantes
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Edição de Lista de Carteiras de Estudante</h3>
        </div>
            <form action="{{ route('academico.carteirasestudantis.edit', [$lista->lst_id]) }}" method="POST" id="form" role="form">
                @csrf
                @method('PUT')
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