@extends('layouts.modulos.default')

@section('title')
    Pessoas
@stop

@section('subtitle')
    Alterar pessoa :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de edição de pessoa</h3>
            </div>
            <form action="{{ route('geral.pessoas.edit', [$pessoa->pes_id]) }}" method="POST" id="form" role="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        @include('Geral::pessoas.includes.formulario')
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop