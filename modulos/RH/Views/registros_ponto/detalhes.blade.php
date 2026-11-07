@extends('layouts.modulos.default')

@section('title') Detalhes do Registro de Horas @stop
@section('subtitle') Eventos do dia {{ $data }} @stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-body p-0 table-responsive">
            @if($eventos->count())
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Data/Hora</th>
                        <th>Origem</th>
                        <th>Status</th>
                        <th>Jornada</th>
                        <th>Atividades</th>
                        <th>Mensagem</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($eventos as $evento)
                        @php($jornada = $evento->jornada_saida ?: $evento->jornada_entrada)
                        <tr>
                            <td>{{ $evento->eva_id }}</td>
                            <td>{{ ucfirst($evento->eva_tipo) }}</td>
                            <td>{{ $evento->eva_data_hora }}</td>
                            <td>{{ $evento->eva_origem === 'idface' ? 'iDFace' : 'Home Office' }}</td>
                            <td>
                                <span class="badge bg-{{ match($evento->eva_status) {
                                    'processado', 'aprovado' => 'success',
                                    'pendente' => 'warning',
                                    default => 'secondary'
                                } }}">{{ $evento->eva_status }}</span>
                            </td>
                            <td>{{ $jornada ? '#' . $jornada->jor_id . ' (' . $jornada->jor_status . ')' : '—' }}</td>
                            <td style="white-space: normal;">{{ $jornada->jor_atividades ?? '—' }}</td>
                            <td>{{ $evento->eva_status_mensagem ?? '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info m-3">Nenhum evento encontrado para este dia.</div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('rh.registrosponto.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
@stop
