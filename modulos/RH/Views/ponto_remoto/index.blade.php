@extends('layouts.modulos.default')

@section('title') Ponto Remoto @stop
@section('subtitle') Registro de Entrada/Saida @stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-clock-o"></i> Registrar Ponto</h3>
                </div>
                <div class="box-body text-center">
                    <p style="font-size: 24px; margin-bottom: 20px;">
                        <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                    </p>

                    <div class="row" style="margin-top: 20px;">
                        <div class="col-xs-6">
                            <form method="POST" action="{{ route('rh.pontoremoto.entrada') }}">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-success btn-lg btn-block" {{ !$estado['pode_entrada'] ? 'disabled' : '' }}>
                                    <i class="fa fa-sign-in"></i> Entrada
                                </button>
                            </form>
                        </div>
                        <div class="col-xs-6">
                            <form method="POST" action="{{ route('rh.pontoremoto.saida') }}">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-lg btn-block" {{ !$estado['pode_saida'] ? 'disabled' : '' }}>
                                    <i class="fa fa-sign-out"></i> Saida
                                </button>
                            </form>
                        </div>
                    </div>

                    <div style="margin-top: 15px;">
                        @if($estado['tem_entrada_aberta'])
                            <span class="label label-success">Entrada em aberto</span>
                        @else
                            <span class="label label-default">Sem entrada em aberto</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-history"></i> Meus registros recentes</h3>
                </div>
                <div class="box-body table-responsive">
                    @if($meusRegistros->count())
                        <table class="table table-striped">
                            <thead><tr><th>Tipo</th><th>Data/Hora</th><th>Status</th></tr></thead>
                            <tbody>
                            @foreach($meusRegistros as $r)
                                <tr>
                                    <td>{{ ucfirst($r->eva_tipo) }}</td>
                                    <td>{{ $r->eva_data_hora }}</td>
                                    <td>
                                        <span class="label label-{{ match($r->eva_status) {
                                            'aprovado', 'processado' => 'success',
                                            'pendente' => 'warning',
                                            'reprovado' => 'danger',
                                            default => 'default'
                                        } }}">{{ $r->eva_status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Nenhum registro recente.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
