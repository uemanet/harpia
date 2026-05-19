@extends('layouts.modulos.default')

@section('title') Aprovação #{{ $evento->eva_id }} @stop
@section('subtitle') Detalhes do Registro @stop

@section('content')
    <div class="box box-primary">
        <div class="box-body">
            <dl class="dl-horizontal">
                <dt>ID</dt><dd>{{ $evento->eva_id }}</dd>
                <dt>Colaborador</dt><dd>{{ $evento->colaborador->pessoa->pes_nome ?? '—' }}</dd>
                <dt>Tipo</dt><dd>{{ ucfirst($evento->eva_tipo) }}</dd>
                <dt>Data/Hora</dt><dd>{{ $evento->eva_data_hora }}</dd>
                <dt>Origem</dt><dd>{{ $evento->eva_origem === 'home_office' ? 'Home Office' : 'iDFace' }}</dd>
                <dt>Status</dt><dd>{{ $evento->eva_status }}</dd>
                <dt>Mensagem</dt><dd>{{ $evento->eva_status_mensagem ?? '—' }}</dd>
            </dl>

            @if($evento->aprovacoes->count())
                <h4>Histórico de Aprovações</h4>
                <table class="table">
                    <thead><tr><th>Data</th><th>Status</th><th>Motivo</th></tr></thead>
                    <tbody>
                    @foreach($evento->aprovacoes as $apr)
                        <tr>
                            <td>{{ $apr->apr_data_aprovacao }}</td>
                            <td>{{ $apr->apr_status }}</td>
                            <td>{{ $apr->apr_motivo ?? '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="box-footer">
            <a href="{{ route('rh.aprovacoesponto.index') }}" class="btn btn-default">Voltar</a>
        </div>
    </div>
@stop
