@extends('layouts.modulos.default')

@section('title') Registro de Horas Remoto @stop
@section('subtitle') Registro de Entrada/Saída @stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title m-0"><i class="fa fa-clock-o"></i> Registrar Ponto</h3>
                </div>
                <div class="card-body text-center">
                    <p class="fs-4 mb-4">
                        <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                    </p>

                    <div class="row mt-3">
                        <div class="col-6">
                            <form method="POST" action="{{ route('rh.pontoremoto.entrada') }}">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-success btn-lg w-100" {{ !$estado['pode_entrada'] ? 'disabled' : '' }}>
                                    <i class="fa fa-sign-in"></i> Entrada
                                </button>
                            </form>
                        </div>
                        <div class="col-6">
                            <button
                                type="button"
                                class="btn btn-danger btn-lg w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-saida"
                                {{ !$estado['pode_saida'] ? 'disabled' : '' }}
                            >
                                <i class="fa fa-sign-out"></i> Saída
                            </button>
                        </div>
                    </div>

                    <div class="mt-3">
                        @if($estado['tem_entrada_aberta'])
                            <span class="badge bg-success">Entrada em aberto</span>
                            @if(!empty($estado['jornada_aberta']) && $estado['jornada_aberta']->jor_entrada_em)
                                <p class="mt-2 mb-0 text-muted">
                                    Aberta em {{ date('d/m/Y H:i', strtotime($estado['jornada_aberta']->jor_entrada_em)) }}
                                </p>
                            @endif
                        @else
                            <span class="badge bg-secondary">Sem entrada em aberto</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title m-0"><i class="fa fa-history"></i> Minhas jornadas remotas</h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    @if($meusRegistros->count())
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Data</th>
                                <th>Entrada</th>
                                <th>Saída</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($meusRegistros as $r)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($r->jor_data_referencia)) }}</td>
                                    <td>{{ $r->jor_entrada_em ? date('H:i', strtotime($r->jor_entrada_em)) : '—' }}</td>
                                    <td>{{ $r->jor_saida_em ? date('H:i', strtotime($r->jor_saida_em)) : '—' }}</td>
                                    <td>
                                        <span class="badge bg-{{ match($r->jor_status) {
                                            'aprovado' => 'success',
                                            'parcial' => 'info',
                                            'pendente', 'aberta' => 'warning',
                                            'reprovado', 'inconsistente' => 'danger',
                                            default => 'secondary'
                                        } }}">{{ $r->jor_status }}</span>
                                        @if($r->jor_atividades)
                                            <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($r->jor_atividades, 70) }}</small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted m-3">Nenhum registro recente.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-saida">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('rh.pontoremoto.saida') }}">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h4 class="modal-title">Registrar saída remota</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="atividades">Atividades executadas*</label>
                            <textarea
                                name="atividades"
                                id="atividades"
                                class="form-control"
                                rows="5"
                                required
                            >{{ old('atividades') }}</textarea>
                            <small class="text-muted">Descreva resumidamente as atividades executadas neste período remoto.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Registrar saída</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
