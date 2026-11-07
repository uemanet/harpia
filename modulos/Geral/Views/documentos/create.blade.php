@extends('layouts.modulos.default')

@section('title')
    Documentos
@stop

@section('subtitle')
    Cadastro de documentos de {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de cadastro de documentos</h3>
            </div>
            <form action="{{ route('geral.pessoas.documentos.create', [$pessoa->pes_id]) }}" method="POST" id="form" role="form" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        @include('Geral::documentos.includes.formulario')
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop
