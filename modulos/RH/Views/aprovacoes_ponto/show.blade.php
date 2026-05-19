@extends('layouts.modulos.default')

@section('title') Aprovação #{{ $evento->eva_id }} @stop
@section('subtitle') Detalhes do Registro @stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">ID</div>
                <div class="col-sm-9">{{ $evento->eva_id }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Colaborador</div>
                <div class="col-sm-9">{{ $evento->colaborador->pessoa->pes_nome ?? '—' }}</div>
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
                <div class="col-sm-9">{{ $evento->eva_origem === 'home_office' ? 'Home Office' : 'iDFace' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Status</div>
                <div class="col-sm-9">{{ $evento->eva_status }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-3 fw-bold">Mensagem</div>
                <div class="col-sm-9">{{ $evento->eva_status_mensagem ?? '—' }}</div>
            </div>

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
        <div class="card-footer">
            <a href="{{ route('rh.aprovacoesponto.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
@stop
