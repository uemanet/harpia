<!-- Atividades Extras -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Atividades Extras</h3>

                <div class="card-tools float-end">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if(!$colaborador->atividades_extras->isEmpty())
                    <table class="table table-bordered">
                        <tr>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Tipo</th>
                            <th>Carga Horária</th>
                            <th>Início</th>
                            <th>Fim</th>
                        </tr>
                        @foreach($colaborador->atividades_extras as $atividade_extra)
                            <tr>
                                <td>{{$atividade_extra->atc_titulo}}</td>
                                <td>{{$atividade_extra->atc_descricao}}</td>
                                <td>{{$atividade_extra->atc_tipo}}</td>
                                <td>{{$atividade_extra->atc_carga_horaria}}</td>
                                <td>{{$atividade_extra->atc_data_inicio}}</td>
                                <td>{{$atividade_extra->atc_data_fim}}</td>
                                <td>

                                    {!! ActionButton::grid([
                                         'type' => 'LINE',
                                         'buttons' => [
                                            [
                                                'classButton' => 'btn btn-primary btn-sm',
                                                'icon' => 'fa fa-pencil',
                                                'route' => 'rh.colaboradores.atividadesextrascolaboradores.edit',
                                                'parameters' => ['id' => $atividade_extra->atc_id],
                                                'label' => '',
                                                'method' => 'get'
                                            ],
                                            [
                                                'classButton' => 'btn-delete btn btn-danger btn-sm',
                                                'icon' => 'fa fa-trash',
                                                'route' => 'rh.colaboradores.atividadesextrascolaboradores.delete',
                                                'id' => $atividade_extra->atc_id,
                                                'label' => '',
                                                'method' => 'post'
                                            ]
                                        ]
                                ]) !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p>Atividades Extras para apresentar</p>
                @endif
            </div>
            <div class="card-footer">
                {!! ActionButton::grid([
                    'type' => 'LINE',
                    'buttons' => [
                        [
                            'classButton' => 'btn btn-primary',
                            'icon' => 'fa fa-plus-square',
                            'route' => 'rh.colaboradores.atividadesextrascolaboradores.create',
                            'parameters' => ['id' => $colaborador->col_id],
                            'label' => ' Nova Atividade Extra',
                            'method' => 'get'
                        ],
                    ]
                ]) !!}
            </div>
        </div>
    </div>
</div>