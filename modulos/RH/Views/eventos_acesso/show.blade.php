@extends('layouts.modulos.default')

@section('title')
    Evento de Acesso #{{ $evento->eva_id }}
@stop

@section('subtitle')
    Detalhes do Evento
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Detalhes do evento</h3>
        </div>
        <div class="box-body">
            <dl class="dl-horizontal">
                <dt>ID</dt><dd>{{ $evento->eva_id }}</dd>
                <dt>Colaborador</dt><dd>{{ $evento->colaborador->pessoa->pes_nome ?? '—' }} ({{ $evento->eva_col_id }})</dd>
                <dt>Tipo</dt><dd>{{ ucfirst($evento->eva_tipo) }}</dd>
                <dt>Data/Hora</dt><dd>{{ $evento->eva_data_hora }}</dd>
                <dt>Origem</dt><dd>{{ $evento->eva_origem === 'idface' ? 'iDFace' : 'Home Office' }}</dd>
                <dt>Status</dt><dd>{{ $evento->eva_status }}</dd>
                <dt>Mensagem</dt><dd>{{ $evento->eva_status_mensagem ?? '—' }}</dd>
                <dt>Hash</dt><dd><code>{{ $evento->eva_hash }}</code></dd>
                <dt>IP Origem</dt><dd>{{ $evento->eva_ip_origem ?? '—' }}</dd>
                <dt>User Agent</dt><dd>{{ $evento->eva_user_agent ?? '—' }}</dd>
                <dt>Observacao</dt><dd><pre>{{ $evento->eva_observacao ? json_encode(json_decode($evento->eva_observacao), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '—' }}</pre></dd>
                <dt>Criado em</dt><dd>{{ $evento->created_at }}</dd>
            </dl>
        </div>
        <div class="box-footer">
            <a href="{{ route('rh.eventosacesso.index') }}" class="btn btn-default">Voltar</a>
        </div>
    </div>
@stop
