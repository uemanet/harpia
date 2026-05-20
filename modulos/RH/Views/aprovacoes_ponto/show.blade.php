@extends('layouts.modulos.default')

@section('title') Aprovação #{{ $jornada->jor_id }} @stop
@section('subtitle') Detalhes da Jornada Remota @stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">ID</div>
                <div class="col-sm-9">{{ $jornada->jor_id }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Colaborador</div>
                <div class="col-sm-9">{{ $jornada->colaborador->pessoa->pes_nome ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Data</div>
                <div class="col-sm-9">{{ date('d/m/Y', strtotime($jornada->jor_data_referencia)) }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Entrada</div>
                <div class="col-sm-9">{{ $jornada->jor_entrada_em ? date('d/m/Y H:i', strtotime($jornada->jor_entrada_em)) : '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Saída</div>
                <div class="col-sm-9">{{ $jornada->jor_saida_em ? date('d/m/Y H:i', strtotime($jornada->jor_saida_em)) : '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Status</div>
                <div class="col-sm-9">{{ $jornada->jor_status }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Horas calculadas</div>
                <div class="col-sm-9">{{ $jornada->jor_horas_calculadas ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Horas aceitas</div>
                <div class="col-sm-9">{{ $jornada->jor_horas_aprovadas ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Atividades</div>
                <div class="col-sm-9" style="white-space: pre-wrap;">{{ $jornada->jor_atividades ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Motivo</div>
                <div class="col-sm-9">{{ $jornada->jor_motivo_aprovacao ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Evento entrada</div>
                <div class="col-sm-9">{{ $jornada->jor_eva_entrada_id ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Evento saída</div>
                <div class="col-sm-9">{{ $jornada->jor_eva_saida_id ?? '—' }}</div>
            </div>

            @if($jornada->aprovacoes->count())
                <h4>Histórico de Aprovações</h4>
                <table class="table">
                    <thead><tr><th>Data</th><th>Status</th><th>Horas aceitas</th><th>Motivo</th></tr></thead>
                    <tbody>
                    @foreach($jornada->aprovacoes as $apr)
                        <tr>
                            <td>{{ $apr->apr_data_aprovacao }}</td>
                            <td>{{ $apr->apr_status }}</td>
                            <td>{{ $apr->apr_horas_aceitas ?? '—' }}</td>
                            <td>{{ $apr->apr_motivo ?? '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('rh.aprovacoesponto.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
@stop
