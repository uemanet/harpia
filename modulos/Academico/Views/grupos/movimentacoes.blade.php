@extends('layouts.modulos.academico')

@section('title')
    Histórico de Movimentação
@stop

@section('subtitle')
    Grupo :: {{ $grupo->grp_nome }}
@stop

@section('content')
    @if(!is_null($movimentacoes))
        <div class="box box-primary">
            <div class="box-header">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tutor</th>
                            <th>Ação</th>
                            <th>Tipo de Tutoria</th>
                            <th>Data de Vínculo</th>
                            <th>Data fim</th>
                            <th>Usuário</th>
                            <th>Feito em</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($movimentacoes as $movimentacao)
                        <tr>
                            <td>{{ $movimentacao["ttg_tut_id"] }}</td>
                            <td>
                                @if($movimentacao["action"] == "Tutor inserido no Grupo")
                                    <span class="label label-success">
                                        {{ $movimentacao["action"] }}
                                    </span>
                                @else
                                    <span class="label label-danger">
                                        {{ $movimentacao["action"] }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $movimentacao["ttg_tipo_tutoria"] }}</td>
                            <td>{{ date("d/m/Y", strtotime($movimentacao["ttg_data_inicio"])) }}</td>
                            <td>
                                @if($movimentacao["ttg_data_fim"])
                                    {{ date("d/m/Y", strtotime($movimentacao["ttg_data_fim"])) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $movimentacao["usuario"] }}</td>
                            <td>
                                {{ date("d/m/Y H:i:s", strtotime($movimentacao["data_hora"]))}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
