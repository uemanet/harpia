@extends('layouts.modulos.default')

@section('title')
    Novo Dispositivo de Acesso
@stop

@section('subtitle')
    Administracao do Controle de Acesso
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Cadastrar dispositivo</h3>
        </div>
        <form method="POST" action="{{ route('rh.dispositivosacesso.create') }}">
            {{ csrf_field() }}
            <div class="box-body">
                @include('RH::dispositivos_acesso.includes.formulario')
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="{{ route('rh.dispositivosacesso.index') }}" class="btn btn-default">Cancelar</a>
            </div>
        </form>
    </div>
@stop
