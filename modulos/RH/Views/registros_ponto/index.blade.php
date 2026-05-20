@extends('layouts.modulos.default')

@section('title') Registro de Horas @stop
@section('subtitle') Visão Unificada @stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0"><i class="fa fa-filter"></i> Filtrar dados</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('rh.registrosponto.index') }}" class="w-100">
                <div class="row">
                    <div class="col-md-2 px-1">
                        <input type="text" name="pes_nome" class="form-control" placeholder="Nome" value="{{ request('pes_nome') }}">
                    </div>
                    <div class="col-md-2 px-1">
                        <select name="setor" class="form-control">
                            <option value="">Todos os setores</option>
                            @foreach(\Modulos\RH\Models\Setor::all() as $setor)
                                <option value="{{ $setor->set_id }}" {{ request('setor') == $setor->set_id ? 'selected' : '' }}>{{ $setor->set_descricao }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 px-1">
                        <select name="origem" class="form-control">
                            <option value="">Todas as origens</option>
                            <option value="idface" {{ request('origem') === 'idface' ? 'selected' : '' }}>iDFace</option>
                            <option value="home_office" {{ request('origem') === 'home_office' ? 'selected' : '' }}>Home Office</option>
                        </select>
                    </div>
                    <div class="col-md-2 px-1">
                        <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}" placeholder="Data início">
                    </div>
                    <div class="col-md-2 px-1">
                        <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}" placeholder="Data fim">
                    </div>
                    <div class="col-md-2 px-1">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-primary card-outline my-2">
        <div class="card-body p-0 table-responsive">
            @if($registros->count())
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Colaborador</th>
                        <th>Setor</th>
                        <th>Data</th>
                        <th>Primeira Entrada</th>
                        <th>Última Saída</th>
                        <th>Total Eventos</th>
                        <th>Origem</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($registros as $r)
                        <tr>
                            <td>{{ $r->pes_nome }}</td>
                            <td>{{ $r->set_descricao ?? '—' }}</td>
                            <td>{{ $r->data }}</td>
                            <td>{{ $r->primeira_entrada ?? '—' }}</td>
                            <td>{{ $r->ultima_saida ?? '—' }}</td>
                            <td>{{ $r->total_eventos }}</td>
                            <td>{{ $r->eva_origem === 'idface' ? 'iDFace' : 'Home Office' }}</td>
                            <td>
                                <a href="{{ route('rh.registrosponto.detalhes', ['col_id' => $r->eva_col_id, 'data' => $r->data]) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-eye"></i> Detalhes
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="card-footer clearfix">
                    {{ $registros->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-info m-3">Nenhum registro encontrado.</div>
            @endif
        </div>
    </div>
@stop
