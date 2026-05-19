@extends('layouts.modulos.default')

@section('title')
    Teste de Dispositivo
@stop

@section('subtitle')
    Módulo RH — Diagnóstico iDFace
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plug"></i> Selecionar Dispositivo</h3>
                </div>
                <div class="box-body">
                    @if($dispositivos->isEmpty())
                        <div class="alert alert-warning">
                            Nenhum dispositivo ativo cadastrado. Cadastre um dispositivo em
                            <a href="{{ route('rh.dispositivosacesso.create') }}">Dispositivos de Acesso</a>.
                        </div>
                    @else
                        <p>Selecione um dispositivo e uma ação para testar a comunicação com o iDFace.</p>
                    @endif
                </div>
            </div>

            @if(session('resultado'))
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-check-circle"></i> Resultado</h3>
                    </div>
                    <div class="box-body">
                        <pre style="max-height: 400px; overflow: auto;">{{ json_encode(session('resultado'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            @foreach($dispositivos as $dispositivo)
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $dispositivo->dis_nome }}</h3>
                        <span class="label label-{{ $dispositivo->dis_status === 'ativo' ? 'success' : 'danger' }} pull-right">
                            {{ $dispositivo->dis_status }}
                        </span>
                    </div>
                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>Identificador</dt>
                            <dd>{{ $dispositivo->dis_identificador }}</dd>
                            <dt>IP</dt>
                            <dd>{{ $dispositivo->dis_ip }}</dd>
                            <dt>Modelo</dt>
                            <dd>{{ $dispositivo->dis_modelo ?? '—' }}</dd>
                            <dt>Tipo</dt>
                            <dd>{{ $dispositivo->dis_tipo }}</dd>
                        </dl>

                        <form method="POST" action="{{ route('rh.testedispositivo.ping') }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-signal"></i> Ping</button>
                        </form>

                        <form method="POST" action="{{ route('rh.testedispositivo.listarusuarios') }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                            <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-users"></i> Listar Usuários</button>
                        </form>

                        <form method="POST" action="{{ route('rh.testedispositivo.consultarlogs') }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                            <button type="submit" class="btn btn-sm btn-warning"><i class="fa fa-list-alt"></i> Consultar Logs</button>
                        </form>

                        <form method="GET" action="{{ route('rh.testedispositivo.systeminfo') }}" style="display:inline; margin-top:5px;">
                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-info-circle"></i> System Info</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
