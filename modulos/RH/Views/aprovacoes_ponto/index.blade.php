@extends('layouts.modulos.default')

@section('title') Aprovação de Horas @stop
@section('subtitle') Gestão de Registros Remotos @stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>
        </div>
        <div class="box-body">
            <form method="GET" action="{{ route('rh.aprovacoesponto.index') }}" class="form-inline">
                <select name="status" class="form-control">
                    <option value="">Pendentes (padrão)</option>
                    <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendentes</option>
                    <option value="aprovado" {{ request('status') === 'aprovado' ? 'selected' : '' }}>Aprovados</option>
                    <option value="reprovado" {{ request('status') === 'reprovado' ? 'selected' : '' }}>Reprovados</option>
                    <option value="todos" {{ request('status') === 'todos' ? 'selected' : '' }}>Todos</option>
                </select>
                <input type="text" name="pes_nome" class="form-control" placeholder="Nome do colaborador" value="{{ request('pes_nome') }}">
                <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}" placeholder="Data início">
                <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}" placeholder="Data fim">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-body table-responsive">
            @if($eventos->count())
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Colaborador</th>
                        <th>Tipo</th>
                        <th>Data/Hora</th>
                        <th>Status</th>
                        <th>Aprovador</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($eventos as $evento)
                        <tr>
                            <td>{{ $evento->eva_id }}</td>
                            <td>{{ $evento->colaborador->pessoa->pes_nome ?? '—' }}</td>
                            <td>{{ ucfirst($evento->eva_tipo) }}</td>
                            <td>{{ $evento->eva_data_hora }}</td>
                            <td>
                                <span class="label label-{{ match($evento->eva_status) {
                                    'aprovado' => 'success',
                                    'reprovado' => 'danger',
                                    'pendente' => 'warning',
                                    default => 'default'
                                } }}">{{ $evento->eva_status }}</span>
                            </td>
                            <td>
                                @php($ultimaAprovacao = $evento->aprovacoes->sortByDesc('apr_data_aprovacao')->first())
                                @if($ultimaAprovacao && $ultimaAprovacao->aprovador && $ultimaAprovacao->aprovador->pessoa)
                                    {{ $ultimaAprovacao->aprovador->pessoa->pes_nome }}
                                    <br><small class="text-muted">{{ date('d/m/Y H:i', strtotime($ultimaAprovacao->apr_data_aprovacao)) }}</small>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($evento->eva_status === 'pendente')
                                    <form method="POST" action="{{ route('rh.aprovacoesponto.aprovar') }}" style="display:inline;">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{ $evento->eva_id }}">
                                        <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Aprovar</button>
                                    </form>
                                    <button type="button" class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#modal-{{ $evento->eva_id }}">
                                        <i class="fa fa-times"></i> Reprovar
                                    </button>
                                @else
                                    <a href="{{ route('rh.aprovacoesponto.show', ['id' => $evento->eva_id]) }}" class="btn btn-xs btn-default">
                                        <i class="fa fa-eye"></i> Ver
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center">{{ $eventos->appends(request()->except('page'))->links('pagination::bootstrap-4') }}</div>
            @else
                <div class="alert alert-info">Nenhum registro encontrado para os filtros informados.</div>
            @endif
        </div>
    </div>

    @foreach($eventos as $evento)
        @if($evento->eva_status === 'pendente')
            <div class="modal fade" id="modal-{{ $evento->eva_id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('rh.aprovacoesponto.reprovar') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $evento->eva_id }}">
                            <div class="modal-header">
                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Reprovar registro</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Motivo da reprovação*</label>
                                    <textarea name="motivo" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Reprovar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@stop
