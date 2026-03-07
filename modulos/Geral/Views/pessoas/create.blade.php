@extends('layouts.modulos.geral')

@section('title')
    Pessoas
@stop

@section('subtitle')
    Cadastro de pessoas
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de pessoas</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('geral.pessoas.create') }}" method="POST" id="form" role="form">
    @csrf
                @include('Geral::pessoas.includes.formulario')

                <div class="row">
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop