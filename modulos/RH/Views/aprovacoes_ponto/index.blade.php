@extends('layouts.modulos.default')

@section('title') Aprovação de Horas @stop
@section('subtitle') Gestão de Registros Remotos @stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0"><i class="fa fa-filter"></i> Filtrar dados</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('rh.aprovacoesponto.index') }}" class="w-100">
                <div class="row">
                    <div class="col-md-2 px-1">
                        <select name="status" class="form-control">
                            <option value="">Pendentes (padrão)</option>
                            <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendentes</option>
                            <option value="aprovado" {{ request('status') === 'aprovado' ? 'selected' : '' }}>Aprovados</option>
                            <option value="parcial" {{ request('status') === 'parcial' ? 'selected' : '' }}>Parciais</option>
                            <option value="reprovado" {{ request('status') === 'reprovado' ? 'selected' : '' }}>Reprovados</option>
                            <option value="inconsistente" {{ request('status') === 'inconsistente' ? 'selected' : '' }}>Inconsistentes</option>
                            <option value="todos" {{ request('status') === 'todos' ? 'selected' : '' }}>Todos</option>
                        </select>
                    </div>
                    <div class="col-md-3 px-1">
                        <input type="text" name="pes_nome" class="form-control" placeholder="Nome do colaborador" value="{{ request('pes_nome') }}">
                    </div>
                    <div class="col-md-2 px-1">
                        <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}" placeholder="Data início">
                    </div>
                    <div class="col-md-2 px-1">
                        <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}" placeholder="Data fim">
                    </div>
                    <div class="col-md-2 px-1">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-primary card-outline my-2">
        <div class="card-body p-0 table-responsive">
            @if($jornadas->count())
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Colaborador</th>
                        <th>Data</th>
                        <th>Entrada</th>
                        <th>Saída</th>
                        <th>Horas calculadas</th>
                        <th>Horas aceitas</th>
                        <th>Atividades</th>
                        <th>Status</th>
                        <th>Aprovador</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($jornadas as $jornada)
                        <tr>
                            <td>{{ $jornada->jor_id }}</td>
                            <td>{{ $jornada->colaborador->pessoa->pes_nome ?? '—' }}</td>
                            <td>{{ date('d/m/Y', strtotime($jornada->jor_data_referencia)) }}</td>
                            <td>{{ $jornada->jor_entrada_em ? date('H:i', strtotime($jornada->jor_entrada_em)) : '—' }}</td>
                            <td>{{ $jornada->jor_saida_em ? date('H:i', strtotime($jornada->jor_saida_em)) : '—' }}</td>
                            <td>{{ $jornada->jor_horas_calculadas ?? '—' }}</td>
                            <td>{{ $jornada->jor_horas_aprovadas ?? '—' }}</td>
                            <td>
                                <div style="max-width: 280px; white-space: normal;">
                                    {{ $jornada->jor_atividades ?? '—' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ match($jornada->jor_status) {
                                    'aprovado' => 'success',
                                    'parcial' => 'info',
                                    'reprovado' => 'danger',
                                    'pendente' => 'warning',
                                    'inconsistente' => 'danger',
                                    default => 'secondary'
                                } }}">{{ $jornada->jor_status }}</span>
                            </td>
                            <td>
                                @php($ultimaAprovacao = $jornada->aprovacoes->sortByDesc('apr_data_aprovacao')->first())
                                @if($ultimaAprovacao && $ultimaAprovacao->aprovador && $ultimaAprovacao->aprovador->pessoa)
                                    {{ $ultimaAprovacao->aprovador->pessoa->pes_nome }}
                                    <br><small class="text-muted">{{ date('d/m/Y H:i', strtotime($ultimaAprovacao->apr_data_aprovacao)) }}</small>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($jornada->jor_status === 'pendente')
                                    <form method="POST" action="{{ route('rh.aprovacoesponto.aprovar') }}" style="display:inline;">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{ $jornada->jor_id }}">
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aprovar</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modal-parcial-{{ $jornada->jor_id }}">
                                        <i class="fa fa-adjust"></i> Parcial
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modal-reprovar-{{ $jornada->jor_id }}">
                                        <i class="fa fa-times"></i> Reprovar
                                    </button>
                                @else
                                    <a href="{{ route('rh.aprovacoesponto.show', ['id' => $jornada->jor_id]) }}" class="btn btn-sm btn-secondary">
                                        <i class="fa fa-eye"></i> Ver
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="card-footer clearfix">
                    {{ $jornadas->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-info m-3">Nenhum registro encontrado para os filtros informados.</div>
            @endif
        </div>
    </div>

    @foreach($jornadas as $jornada)
        @if($jornada->jor_status === 'pendente')
            <div class="modal fade" id="modal-parcial-{{ $jornada->jor_id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('rh.aprovacoesponto.parcial') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $jornada->jor_id }}">
                            <div class="modal-header">
                                <h4 class="modal-title">Aprovar parcialmente</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Horas aceitas*</label>
                                    <input type="text" name="horas_aceitas" class="form-control" placeholder="Ex.: 03:00 ou 03:00:00" required>
                                    <small class="text-muted">Horas calculadas para a jornada: {{ $jornada->jor_horas_calculadas ?? '00:00:00' }}</small>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Motivo da aprovação parcial*</label>
                                    <textarea name="motivo" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-info">Salvar parcial</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-reprovar-{{ $jornada->jor_id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('rh.aprovacoesponto.reprovar') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $jornada->jor_id }}">
                            <div class="modal-header">
                                <h4 class="modal-title">Reprovar registro</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Motivo da reprovação*</label>
                                    <textarea name="motivo" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Reprovar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@stop
