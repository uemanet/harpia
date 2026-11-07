@extends('layouts.modulos.default')

@section('title')
    Novo Dispositivo de Acesso
@stop

@section('subtitle')
    Administração do Controle de Acesso
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Cadastrar dispositivo</h3>
        </div>
        <form method="POST" action="{{ route('rh.dispositivosacesso.create') }}">
            {{ csrf_field() }}
            <div class="card-body">
                @include('RH::dispositivos_acesso.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="{{ route('rh.dispositivosacesso.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@stop
