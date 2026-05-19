@extends('layouts.modulos.default')

@section('title')
    Dispositivos de Acesso
@stop

@section('subtitle')
    Administração do Controle de Acesso
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>
        </div>
        <div class="box-body">
            <form method="GET" action="{{ route('rh.dispositivosacesso.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="dis_nome" value="{{ request('dis_nome') }}" placeholder="Nome do dispositivo">
                    </div>
                    <div class="col-md-3">
                        <select name="dis_tipo" class="form-control">
                            <option value="">Todos os tipos</option>
                            <option value="entrada" {{ request('dis_tipo') === 'entrada' ? 'selected' : '' }}>Entrada</option>
                            <option value="saida" {{ request('dis_tipo') === 'saida' ? 'selected' : '' }}>Saída</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="dis_status" class="form-control">
                            <option value="">Todos os status</option>
                            <option value="ativo" {{ request('dis_status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ request('dis_status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="{{ route('rh.dispositivosacesso.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Novo dispositivo
            </a>
        </div>
        <div class="box-body table-responsive">
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
                                <span class="label {{ $dispositivo->dis_status === 'ativo' ? 'label-success' : 'label-default' }}">
                                    {{ ucfirst($dispositivo->dis_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('rh.dispositivosacesso.edit', ['id' => $dispositivo->dis_id]) }}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-pencil"></i> Editar
                                </a>

                                <form method="POST" action="{{ route('rh.dispositivosacesso.ping', ['id' => $dispositivo->dis_id]) }}" style="display: inline;">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-exchange"></i> Ping
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('rh.dispositivosacesso.sincronizarmapeamento', ['id' => $dispositivo->dis_id]) }}" style="display: inline;">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-xs btn-warning">
                                        <i class="fa fa-refresh"></i> Sincronizar
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('rh.dispositivosacesso.delete') }}" style="display: inline;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $dispositivo->dis_id }}">
                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Tem certeza que deseja excluir este dispositivo?')">
                                        <i class="fa fa-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="text-center">{{ $dispositivos->appends(request()->except('page'))->links('pagination::bootstrap-4') }}</div>
            @else
                <div class="alert alert-info" style="margin-bottom: 0;">
                    Nenhum dispositivo encontrado para os filtros informados.
                </div>
            @endif
        </div>
    </div>
@stop
