<!-- Salários Base -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Salários Base</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(!$fonte_pagadora->vinculos_fontes_pagadoras->isEmpty())
                    <table class="table table-bordered">
                        <tr>
                            <th>Vínculo</th>
                            <th>Pago por unidade</th>
                            <th>Valor</th>

                        </tr>
                        @foreach($fonte_pagadora->vinculos_fontes_pagadoras as $vinculo_fonte_pagadora)
                            <tr>
                                <td>{{$vinculo_fonte_pagadora->vinculo->vin_descricao}}</td>
                                <td>{{$vinculo_fonte_pagadora->vfp_unidade ? 'Sim' : 'Não'}}</td>
                                <td>{{$vinculo_fonte_pagadora->vfp_valor ? 'R$ '.$vinculo_fonte_pagadora->vfp_valor : ''}}</td>
                                <td>

                                    {!! ActionButton::grid([
                                         'type' => 'LINE',
                                         'buttons' => [
                                            [
                                                'classButton' => 'btn btn-primary btn-sm',
                                                'icon' => 'fa fa-pencil',
                                                'route' => 'rh.fontespagadoras.vinculosfontespagadoras.edit',
                                                'parameters' => ['id' => $vinculo_fonte_pagadora->vfp_id],
                                                'label' => '',
                                                'method' => 'get'
                                            ],
                                            [
                                                'classButton' => 'btn-delete btn btn-danger btn-sm',
                                                'icon' => 'fa fa-trash',
                                                'route' => 'rh.fontespagadoras.vinculosfontespagadoras.delete',
                                                'id' => $vinculo_fonte_pagadora->vfp_id,
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
                    <p>Salários Base para apresentar</p>
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
                            'route' => 'rh.fontespagadoras.vinculosfontespagadoras.create',
                            'parameters' => ['id' => $fonte_pagadora->fpg_id],
                            'label' => ' Novo Salário Base',
                            'method' => 'get'
                        ],
                    ]
                ]) !!}
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>