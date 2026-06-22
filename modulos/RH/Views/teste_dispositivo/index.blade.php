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
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title m-0"><i class="fa fa-plug"></i> Selecionar Dispositivo</h3>
                </div>
                <div class="card-body">
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
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title m-0"><i class="fa fa-check-circle"></i> Resultado</h3>
                    </div>
                    <div class="card-body">
                        <pre style="max-height: 400px; overflow: auto;">{{ json_encode(session('resultado'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            @foreach($dispositivos as $dispositivo)
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title m-0">{{ $dispositivo->dis_nome }}</h3>
                        <span class="badge {{ $dispositivo->dis_status === 'ativo' ? 'bg-success' : 'bg-danger' }} float-end">
                            {{ $dispositivo->dis_status }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Identificador</div>
                            <div class="col-sm-8">{{ $dispositivo->dis_identificador }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">IP</div>
                            <div class="col-sm-8">{{ $dispositivo->dis_ip }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Modelo</div>
                            <div class="col-sm-8">{{ $dispositivo->dis_modelo ?? '—' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Tipo</div>
                            <div class="col-sm-8">{{ $dispositivo->dis_tipo }}</div>
                        </div>

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
                            <button type="submit" class="btn btn-sm btn-secondary"><i class="fa fa-info-circle"></i> System Info</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
