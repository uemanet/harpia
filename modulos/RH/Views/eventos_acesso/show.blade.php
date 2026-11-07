@extends('layouts.modulos.default')

@section('title')
    Evento de Acesso #{{ $evento->eva_id }}
@stop

@section('subtitle')
    Detalhes do Evento
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Detalhes do evento</h3>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">ID</div>
                <div class="col-sm-9">{{ $evento->eva_id }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Colaborador</div>
                <div class="col-sm-9">{{ $evento->colaborador->pessoa->pes_nome ?? '—' }} ({{ $evento->eva_col_id }})</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Tipo</div>
                <div class="col-sm-9">{{ ucfirst($evento->eva_tipo) }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Data/Hora</div>
                <div class="col-sm-9">{{ $evento->eva_data_hora }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Origem</div>
                <div class="col-sm-9">{{ $evento->eva_origem === 'idface' ? 'iDFace' : 'Home Office' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Status</div>
                <div class="col-sm-9">{{ $evento->eva_status }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Mensagem</div>
                <div class="col-sm-9">{{ $evento->eva_status_mensagem ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Hash</div>
                <div class="col-sm-9"><code>{{ $evento->eva_hash }}</code></div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">IP Origem</div>
                <div class="col-sm-9">{{ $evento->eva_ip_origem ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">User Agent</div>
                <div class="col-sm-9">{{ $evento->eva_user_agent ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Observação</div>
                <div class="col-sm-9"><pre>{{ $evento->eva_observacao ? json_encode(json_decode($evento->eva_observacao), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '—' }}</pre></div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Criado em</div>
                <div class="col-sm-9">{{ $evento->created_at }}</div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('rh.eventosacesso.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
@stop
