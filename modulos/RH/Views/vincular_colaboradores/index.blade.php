@extends('layouts.modulos.default')

@section('title')
    Vincular Colaboradores
@stop

@section('subtitle')
    Mapeamento Colaboradores ↔ iDFace
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Selecionar dispositivo</h3>
        </div>
        <div class="box-body">
            <form method="GET" action="{{ route('rh.vincularcolaboradores.index') }}" class="form-inline">
                <select name="dis_id" class="form-control" style="width: 300px;">
                    <option value="">Selecione um dispositivo ativo</option>
                    @foreach($dispositivos as $d)
                        <option value="{{ $d->dis_id }}" {{ request('dis_id') == $d->dis_id ? 'selected' : '' }}>
                            {{ $d->dis_nome }} ({{ $d->dis_identificador }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Consultar</button>
            </form>

            @if($dispositivo)
            <div style="margin-top: 10px;">
                <form method="POST" action="{{ route('rh.vincularcolaboradores.sincronizar') }}" style="display:inline;">
                    {{ csrf_field() }}
                    <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                    <button type="submit" class="btn btn-warning"><i class="fa fa-refresh"></i> Sincronizar</button>
                </form>
                <a href="{{ route('rh.vincularcolaboradores.exportarcsv', ['id' => $dispositivo->dis_id]) }}" class="btn btn-success" style="margin-left: 5px;">
                    <i class="fa fa-download"></i> Exportar CSV
                </a>
            </div>
            @endif
        </div>
    </div>

    @if($resumo)
        <div class="row">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Total</span><span class="info-box-number">{{ $resumo['total'] }}</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Vinculados</span><span class="info-box-number">{{ $resumo['vinculados'] }}</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-exclamation-triangle"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Pendentes</span><span class="info-box-number">{{ $resumo['pendentes'] }}</span></div>
                </div>
            </div>
        </div>
    @endif

    @if($dispositivo)
        <div class="box box-primary">
            <div class="box-header">
                <form method="GET" action="{{ route('rh.vincularcolaboradores.index') }}" class="form-inline">
                    <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                    <select name="status" class="form-control">
                        <option value="">Todos</option>
                        <option value="vinculado" {{ request('status') === 'vinculado' ? 'selected' : '' }}>Vinculados</option>
                        <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendentes</option>
                    </select>
                    <input type="text" name="busca" class="form-control" placeholder="Buscar..." value="{{ request('busca') }}">
                    <button type="submit" class="btn btn-default">Filtrar</button>
                </form>
            </div>
            <div class="box-body table-responsive">
                @if(!empty($usuarios))
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Registration</th>
                            <th>Nome no iDFace</th>
                            <th>Colaborador Harpia</th>
                            <th>Status</th>
                            <th>Acoes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario['user_id'] }}</td>
                                <td>{{ $usuario['registration'] }}</td>
                                <td>{{ $usuario['nome'] }}</td>
                                <td>{{ $usuario['colaborador_nome'] ?? '—' }}</td>
                                <td>
                                    <span class="label label-{{ $usuario['status_vinculo'] === 'vinculado' ? 'success' : 'warning' }}">
                                        {{ $usuario['status_vinculo'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($usuario['status_vinculo'] === 'pendente')
                                        <form method="POST" action="{{ route('rh.vincularcolaboradores.vincular') }}" style="display: inline;">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                                            <input type="hidden" name="user_id" value="{{ $usuario['user_id'] }}">
                                            <select name="col_id" class="form-control input-sm" style="width: 200px; display: inline;">
                                                <option value="">Selecione um colaborador</option>
                                                @foreach($colaboradores as $colId => $colNome)
                                                    <option value="{{ $colId }}">{{ $colNome }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-xs btn-success">Vincular</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('rh.vincularcolaboradores.desvincular') }}" style="display: inline;">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                                            <input type="hidden" name="user_id" value="{{ $usuario['user_id'] }}">
                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Tem certeza?')">Desvincular</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">Selecione um dispositivo para visualizar os usuarios.</div>
                @endif
            </div>
        </div>
    @endif
@stop
