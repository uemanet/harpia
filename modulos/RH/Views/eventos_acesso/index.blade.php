@extends('layouts.modulos.default')

@section('title')
    Eventos de Acesso
@stop

@section('subtitle')
    Auditoria de Eventos
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>
        </div>
        <div class="box-body">
            <form method="GET" action="{{ route('rh.eventosacesso.index') }}" class="form-inline">
                <input type="text" name="pes_nome" class="form-control" placeholder="Nome" value="{{ request('pes_nome') }}">
                <select name="eva_tipo" class="form-control">
                    <option value="">Todos os tipos</option>
                    <option value="entrada" {{ request('eva_tipo') === 'entrada' ? 'selected' : '' }}>Entrada</option>
                    <option value="saida" {{ request('eva_tipo') === 'saida' ? 'selected' : '' }}>Saída</option>
                </select>
                <select name="eva_origem" class="form-control">
                    <option value="">Todas as origens</option>
                    <option value="idface" {{ request('eva_origem') === 'idface' ? 'selected' : '' }}>iDFace</option>
                    <option value="home_office" {{ request('eva_origem') === 'home_office' ? 'selected' : '' }}>Home Office</option>
                </select>
                <select name="eva_status" class="form-control">
                    <option value="">Todos os status</option>
                    <option value="bruto" {{ request('eva_status') === 'bruto' ? 'selected' : '' }}>Bruto</option>
                    <option value="processado" {{ request('eva_status') === 'processado' ? 'selected' : '' }}>Processado</option>
                    <option value="pendente" {{ request('eva_status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="aprovado" {{ request('eva_status') === 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                    <option value="reprovado" {{ request('eva_status') === 'reprovado' ? 'selected' : '' }}>Reprovado</option>
                    <option value="erro" {{ request('eva_status') === 'erro' ? 'selected' : '' }}>Erro</option>
                    <option value="duplicado" {{ request('eva_status') === 'duplicado' ? 'selected' : '' }}>Duplicado</option>
                </select>
                <button type="submit" class="btn btn-primary">Buscar</button>
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
                        <th>Origem</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($eventos as $evento)
                        <tr>
                            <td>{{ $evento->eva_id }}</td>
                            <td>{{ $evento->pes_nome ?? '—' }}</td>
                            <td>{{ ucfirst($evento->eva_tipo) }}</td>
                            <td>{{ $evento->eva_data_hora }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $evento->eva_origem)) }}</td>
                            <td>
                                <span class="label label-{{ match($evento->eva_status) {
                                    'processado', 'aprovado' => 'success',
                                    'pendente' => 'warning',
                                    'reprovado', 'erro' => 'danger',
                                    'duplicado' => 'info',
                                    default => 'default'
                                } }}">
                                    {{ $evento->eva_status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('rh.eventosacesso.show', ['id' => $evento->eva_id]) }}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center">{{ $eventos->appends(request()->except('page'))->links('pagination::bootstrap-4') }}</div>
            @else
                <div class="alert alert-info">Nenhum evento encontrado.</div>
            @endif
        </div>
    </div>
@stop
