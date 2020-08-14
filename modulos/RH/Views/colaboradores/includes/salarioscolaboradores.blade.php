<!-- Contas do Colaborador -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Salário do Colaborador</h3>

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
                            <th>Conta Colaborador</th>
                            <th>Fonte Pagadora</th>
                            <th>Unidade</th>
                            <th>Valor</th>
                            <th>Valor Liquido</th>
                            <th>Data de Inicio</th>
                            <th>Data de Fim</th>
                            <th>Data de Cadastro</th>
                        </tr>
                        @foreach($colaboradores->contas_colaboradores as $conta_colaborador)
                            @foreach($conta_colaborador->salarios_colaboradores as $salario_colaborador)
                            <tr>
                                <td>{{$salario_colaborador->conta->ccb_conta}}</td>
                                <td>{{$salario_colaborador->vinculo->vfp_valor}}</td>
                                <td>{{$salario_colaborador->scb_unidade}}</td>
                                <td>{{$salario_colaborador->scb_valor}}</td>
                                <td>{{$salario_colaborador->scb_valor_liquido}}</td>
                                <td>{{$salario_colaborador->scb_data_inicio}}</td>
                                <td>{{$salario_colaborador->scb_data_fim}}</td>
                                <td>{{$salario_colaborador->scb_data_cadastro}}</td>
                                <td>


                                </td>
                            </tr>
                            @endforeach
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
                            'route' => 'rh.colaboradores.salarioscolaboradores.create',
                            'parameters' => ['id' => $colaborador->col_id],
                            'label' => ' Novo Salário',
                            'method' => 'get'
                        ],
                    ]
                ]) !!}
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>