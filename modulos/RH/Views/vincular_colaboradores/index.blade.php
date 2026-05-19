@extends('layouts.modulos.default')

@section('title')
    Vincular Colaboradores
@stop

@section('subtitle')
    Mapeamento Colaboradores ↔ iDFace
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0"><i class="fa fa-filter"></i> Selecionar dispositivo</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('rh.vincularcolaboradores.index') }}" class="d-flex">
                <select name="dis_id" class="form-control me-2" style="width: 300px;" onchange="this.form.submit()">
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
            <div class="mt-2">
                <form method="POST" action="{{ route('rh.vincularcolaboradores.sincronizar') }}" style="display:inline;">
                    {{ csrf_field() }}
                    <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                    <button type="submit" class="btn btn-warning"><i class="fa fa-refresh"></i> Sincronizar</button>
                </form>
                <a href="{{ route('rh.vincularcolaboradores.exportarcsv', ['id' => $dispositivo->dis_id]) }}" class="btn btn-success ms-1">
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
                    <span class="info-box-icon text-bg-primary"><i class="fa fa-users"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Total</span><span class="info-box-number">{{ $resumo['total'] }}</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon text-bg-success"><i class="fa fa-check"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Vinculados</span><span class="info-box-number">{{ $resumo['vinculados'] }}</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon text-bg-warning"><i class="fa fa-exclamation-triangle"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Pendentes</span><span class="info-box-number">{{ $resumo['pendentes'] }}</span></div>
                </div>
            </div>
        </div>
    @endif

    @if($dispositivo)
        <div class="card card-primary card-outline my-2">
            <div class="card-header">
                <form method="GET" action="{{ route('rh.vincularcolaboradores.index') }}" class="d-flex">
                    <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                    <select name="status" class="form-control me-2" style="width: 150px;">
                        <option value="">Todos</option>
                        <option value="vinculado" {{ request('status') === 'vinculado' ? 'selected' : '' }}>Vinculados</option>
                        <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendentes</option>
                    </select>
                    <input type="text" name="busca" class="form-control me-2" placeholder="Buscar..." value="{{ request('busca') }}">
                    <button type="submit" class="btn btn-secondary">Filtrar</button>
                </form>
            </div>
            <div class="card-body p-0 table-responsive">
                @if(!empty($usuarios))
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Registration</th>
                            <th>Nome no iDFace</th>
                            <th>Colaborador Harpia</th>
                            <th>Status</th>
                            <th>Ações</th>
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
                                    <span class="badge {{ $usuario['status_vinculo'] === 'vinculado' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $usuario['status_vinculo'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($usuario['status_vinculo'] === 'pendente')
                                        <form method="POST" action="{{ route('rh.vincularcolaboradores.vincular') }}" style="display: inline;">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                                            <input type="hidden" name="user_id" value="{{ $usuario['user_id'] }}">
                                            <select name="col_id" class="form-control form-control-sm" style="width: 200px; display: inline;">
                                                <option value="">Selecione um colaborador</option>
                                                @foreach($colaboradores as $colId => $colNome)
                                                    <option value="{{ $colId }}">{{ $colNome }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-success">Vincular</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('rh.vincularcolaboradores.desvincular') }}" style="display: inline;">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                                            <input type="hidden" name="user_id" value="{{ $usuario['user_id'] }}">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Desvincular</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info m-3">Selecione um dispositivo para visualizar os usuários.</div>
                @endif
            </div>
        </div>
    @endif
@stop
