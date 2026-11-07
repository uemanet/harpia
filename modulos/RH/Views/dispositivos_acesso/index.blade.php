@extends('layouts.modulos.default')

@section('title')
    Dispositivos de Acesso
@stop

@section('subtitle')
    Administração do Controle de Acesso
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0"><i class="fa fa-filter"></i> Filtrar dados</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('rh.dispositivosacesso.index') }}">
                <div class="row">
                    <div class="col-md-4 px-1">
                        <input type="text" class="form-control" name="dis_nome" value="{{ request('dis_nome') }}" placeholder="Nome do dispositivo">
                    </div>
                    <div class="col-md-3 px-1">
                        <select name="dis_tipo" class="form-control">
                            <option value="">Todos os tipos</option>
                            <option value="entrada" {{ request('dis_tipo') === 'entrada' ? 'selected' : '' }}>Entrada</option>
                            <option value="saida" {{ request('dis_tipo') === 'saida' ? 'selected' : '' }}>Saída</option>
                        </select>
                    </div>
                    <div class="col-md-3 px-1">
                        <select name="dis_status" class="form-control">
                            <option value="">Todos os status</option>
                            <option value="ativo" {{ request('dis_status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ request('dis_status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-2 px-1">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-primary card-outline my-2">
        <div class="card-header">
            <a href="{{ route('rh.dispositivosacesso.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Novo dispositivo
            </a>
        </div>
        <div class="card-body p-0 table-responsive">
            @if($dispositivos->count())
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Identificador</th>
                        <th>Tipo</th>
                        <th>IP</th>
                        <th>Modelo</th>
                        <th>Status</th>
                        <th style="width: 320px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($dispositivos as $dispositivo)
                        <tr>
                            <td>{{ $dispositivo->dis_nome }}</td>
                            <td>{{ $dispositivo->dis_identificador }}</td>
                            <td>{{ ucfirst($dispositivo->dis_tipo) }}</td>
                            <td>{{ $dispositivo->dis_ip ?: '-' }}</td>
                            <td>{{ $dispositivo->dis_modelo ?: '-' }}</td>
                            <td>
                                <span class="badge {{ $dispositivo->dis_status === 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($dispositivo->dis_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('rh.dispositivosacesso.edit', ['id' => $dispositivo->dis_id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-pencil"></i> Editar
                                </a>

                                <form method="POST" action="{{ route('rh.dispositivosacesso.ping', ['id' => $dispositivo->dis_id]) }}" style="display: inline;">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-sm btn-info">
                                        <i class="fa fa-exchange"></i> Ping
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('rh.dispositivosacesso.sincronizarmapeamento', ['id' => $dispositivo->dis_id]) }}" style="display: inline;">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> Sincronizar
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('rh.dispositivosacesso.delete') }}" style="display: inline;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $dispositivo->dis_id }}">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este dispositivo?')">
                                        <i class="fa fa-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="card-footer clearfix">
                    {{ $dispositivos->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-info m-3">
                    Nenhum dispositivo encontrado para os filtros informados.
                </div>
            @endif
        </div>
    </div>
@stop
