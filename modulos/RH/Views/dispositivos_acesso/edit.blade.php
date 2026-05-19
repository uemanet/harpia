@extends('layouts.modulos.default')

@section('title')
    Editar Dispositivo de Acesso
@stop

@section('subtitle')
    Administração do Controle de Acesso
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Editar dispositivo</h3>
        </div>
        <form method="POST" action="{{ route('rh.dispositivosacesso.edit', ['id' => $dispositivo->dis_id]) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="box-body">
                @include('RH::dispositivos_acesso.includes.formulario')
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Salvar alterações</button>
                <a href="{{ route('rh.dispositivosacesso.index') }}" class="btn btn-default">Voltar</a>
            </div>
        </form>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Ações sensíveis</h3>
        </div>
        <div class="box-body">
            <form method="POST" action="{{ route('rh.dispositivosacesso.regenerartoken', ['id' => $dispositivo->dis_id]) }}" style="display: inline;">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-warning" onclick="return confirm('Tem certeza que deseja regenerar o token deste dispositivo?')">
                    <i class="fa fa-key"></i> Regenerar token
                </button>
            </form>

            <form method="POST" action="{{ route('rh.dispositivosacesso.ping', ['id' => $dispositivo->dis_id]) }}" style="display: inline;">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-info">
                    <i class="fa fa-exchange"></i> Ping
                </button>
            </form>

            <form method="POST" action="{{ route('rh.dispositivosacesso.sincronizarmapeamento', ['id' => $dispositivo->dis_id]) }}" style="display: inline;">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-refresh"></i> Sincronizar Mapeamento
                </button>
            </form>
        </div>
    </div>
@stop
