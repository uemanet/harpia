@extends('layouts.modulos.default')

@section('title')
    Editar Permissão
@stop

@section('subtitle')
    {{$permissao->prm_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Editar Permissão</h3>
            </div>
            <form action="{{ route('seguranca.permissoes.edit', [$permissao->prm_id]) }}" method="POST" id="form" role="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        @include('Seguranca::permissoes.includes.formulario')
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop