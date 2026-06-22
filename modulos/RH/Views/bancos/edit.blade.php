@extends('layouts.modulos.default')

@section('title')
    Bancos
@stop

@section('subtitle')
    Edição de banco
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de banco</h3>
        </div>
        <form action="{{ route('rh.bancos.edit', [$banco->ban_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('RH::bancos.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
