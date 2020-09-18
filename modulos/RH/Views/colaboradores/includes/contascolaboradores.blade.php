<!-- Contas do Colaborador -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Contas do Colaborador</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(!$colaborador->contas_colaboradores->isEmpty())
                    <table class="table table-bordered">
                        <tr>
                            <th>Banco</th>
                            <th>Agencia</th>
                            <th>Conta</th>
                            <th>Variacao</th>
                        </tr>
                        @foreach($colaborador->contas_colaboradores as $conta_colaborador)
                            <tr>
                                <td>{{$conta_colaborador->banco->ban_nome}}</td>
                                <td>{{$conta_colaborador->ccb_agencia}}</td>
                                <td>{{$conta_colaborador->ccb_conta}}</td>
                                <td>{{$conta_colaborador->ccb_variacao}}</td>
                                <td>

                                    {!! ActionButton::grid([
                                         'type' => 'LINE',
                                         'buttons' => [
                                            [
                                                'classButton' => 'btn btn-primary btn-sm',
                                                'icon' => 'fa fa-pencil',
                                                'route' => 'rh.colaboradores.contascolaboradores.edit',
                                                'parameters' => ['id' => $conta_colaborador->ccb_id],
                                                'label' => '',
                                                'method' => 'get'
                                            ],
                                            [
                                                'classButton' => 'btn-delete btn btn-danger btn-sm',
                                                'icon' => 'fa fa-trash',
                                                'route' => 'rh.colaboradores.contascolaboradores.delete',
                                                'id' => $conta_colaborador->ccb_id,
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
                    <p>O colaborador não possui conta de banco cadastrada</p>
                @endif
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {!! ActionButton::grid([
                    'type' => 'LINE',
                    'buttons' => [
                        [
                            'classButton' => 'btn btn-primary',
                            'icon' => 'fa fa-plus-square',
                            'route' => 'rh.colaboradores.contascolaboradores.create',
                            'parameters' => ['id' => $colaborador->col_id],
                            'label' => ' Nova Conta',
                            'method' => 'get'
                        ],
                    ]
                ]) !!}
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>