@extends('layouts.modulos.default')

@section('title') Detalhes do Ponto @stop
@section('subtitle') Eventos do dia {{ $data }} @stop

@section('content')
    <div class="box box-primary">
        <div class="box-body table-responsive">
            @if($eventos->count())
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Data/Hora</th>
                        <th>Origem</th>
                        <th>Status</th>
                        <th>Mensagem</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($eventos as $evento)
                        <tr>
                            <td>{{ $evento->eva_id }}</td>
                            <td>{{ ucfirst($evento->eva_tipo) }}</td>
                            <td>{{ $evento->eva_data_hora }}</td>
                            <td>{{ $evento->eva_origem === 'idface' ? 'iDFace' : 'Home Office' }}</td>
                            <td>
                                <span class="label label-{{ match($evento->eva_status) {
                                    'processado', 'aprovado' => 'success',
                                    'pendente' => 'warning',
                                    default => 'default'
                                } }}">
                                    {{ $evento->eva_status }}
                                </span>
                            </td>
                            <td>{{ $evento->eva_status_mensagem ?? '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">Nenhum evento encontrado para este dia.</div>
            @endif
        </div>
        <div class="box-footer">
            <a href="{{ route('rh.registrosponto.index') }}" class="btn btn-default">Voltar</a>
        </div>
    </div>
@stop
