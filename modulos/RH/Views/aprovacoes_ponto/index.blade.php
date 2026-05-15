@extends('layouts.modulos.default')

@section('title') Aprovacoes de Ponto @stop
@section('subtitle') Gestao de Registros Remotos @stop

@section('content')
    <div class="box box-primary">
        <div class="box-body table-responsive">
            @if($pendentes->count())
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Colaborador</th>
                        <th>Tipo</th>
                        <th>Data/Hora</th>
                        <th>Status</th>
                        <th>Acoes</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pendentes as $evento)
                        <tr>
                            <td>{{ $evento->eva_id }}</td>
                            <td>{{ $evento->colaborador->pessoa->pes_nome ?? '—' }}</td>
                            <td>{{ ucfirst($evento->eva_tipo) }}</td>
                            <td>{{ $evento->eva_data_hora }}</td>
                            <td><span class="label label-warning">{{ $evento->eva_status }}</span></td>
                            <td>
                                <form method="POST" action="{{ route('rh.aprovacoesponto.aprovar') }}" style="display:inline;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $evento->eva_id }}">
                                    <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Aprovar</button>
                                </form>
                                <button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-{{ $evento->eva_id }}">
                                    <i class="fa fa-times"></i> Reprovar
                                </button>

                                <div class="modal fade" id="modal-{{ $evento->eva_id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('rh.aprovacoesponto.reprovar') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{ $evento->eva_id }}">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Reprovar registro</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Motivo da reprovacao*</label>
                                                        <textarea name="motivo" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">Reprovar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center">{{ $pendentes->links('pagination::bootstrap-4') }}</div>
            @else
                <div class="alert alert-info">Nenhuma pendencia para aprovar.</div>
            @endif
        </div>
    </div>
@stop
