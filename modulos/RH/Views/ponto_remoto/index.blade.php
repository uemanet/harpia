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
                            <form method="POST" action="{{ route('rh.pontoremoto.saida') }}">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-lg w-100" {{ !$estado['pode_saida'] ? 'disabled' : '' }}>
                                    <i class="fa fa-sign-out"></i> Saída
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-3">
                        @if($estado['tem_entrada_aberta'])
                            <span class="badge bg-success">Entrada em aberto</span>
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
                    <h3 class="card-title m-0"><i class="fa fa-history"></i> Meus registros pendentes</h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    @if($meusRegistros->count())
                        <table class="table table-striped">
                            <thead><tr><th>Tipo</th><th>Data/Hora</th><th>Status</th></tr></thead>
                            <tbody>
                            @foreach($meusRegistros as $r)
                                <tr>
                                    <td>{{ ucfirst($r->eva_tipo) }}</td>
                                    <td>{{ $r->eva_data_hora }}</td>
                                    <td>
                                        <span class="badge bg-{{ match($r->eva_status) {
                                            'aprovado', 'processado' => 'success',
                                            'pendente' => 'warning',
                                            'reprovado' => 'danger',
                                            default => 'secondary'
                                        } }}">{{ $r->eva_status }}</span>
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
@stop
