@extends('layouts.modulos.default')

@section('title') Registros de Ponto @stop
@section('subtitle') Visao Unificada @stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>
        </div>
        <div class="box-body">
            <form method="GET" action="{{ route('rh.registrosponto.index') }}" class="form-inline">
                <input type="text" name="pes_nome" class="form-control" placeholder="Nome" value="{{ request('pes_nome') }}">
                <select name="setor" class="form-control">
                    <option value="">Todos os setores</option>
                    @foreach(\Modulos\RH\Models\Setor::all() as $setor)
                        <option value="{{ $setor->set_id }}" {{ request('setor') == $setor->set_id ? 'selected' : '' }}>{{ $setor->set_descricao }}</option>
                    @endforeach
                </select>
                <select name="origem" class="form-control">
                    <option value="">Todas as origens</option>
                    <option value="idface" {{ request('origem') === 'idface' ? 'selected' : '' }}>iDFace</option>
                    <option value="home_office" {{ request('origem') === 'home_office' ? 'selected' : '' }}>Home Office</option>
                </select>
                <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}" placeholder="Data inicio">
                <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}" placeholder="Data fim">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-body table-responsive">
            @if($registros->count())
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Colaborador</th>
                        <th>Setor</th>
                        <th>Data</th>
                        <th>Primeira Entrada</th>
                        <th>Ultima Saida</th>
                        <th>Total Eventos</th>
                        <th>Origem</th>
                        <th>Acoes</th>
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
                                <a href="{{ route('rh.registrosponto.detalhes', ['col_id' => $r->eva_col_id, 'data' => $r->data]) }}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-eye"></i> Detalhes
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center">{{ $registros->appends(request()->except('page'))->links('pagination::bootstrap-4') }}</div>
            @else
                <div class="alert alert-info">Nenhum registro encontrado.</div>
            @endif
        </div>
    </div>
@stop
