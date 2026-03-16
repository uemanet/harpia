<!-- Documentos -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title">Documentos</h3>

                <div class="card-tools pull-right">
                    <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(!$pessoa->documentos->isEmpty())
                    <table class="table table-bordered">
                        <tr>
                            <th>Tipo</th>
                            <th>Numero do Documento</th>
                            <th>Orgão Emissor</th>
                            <th>Data de Emissão</th>
                            <th>Observação</th>
                            <th></th>
                        </tr>
                        @foreach($pessoa->documentos as $documento)
                            <tr>
                                <td>{{$documento->tipo_documento->tpd_nome}}</td>
                                @if($documento->tipo_documento->tpd_id == 2)
                                    <td>{{Format::mask($documento->doc_conteudo, '###.###.###-##')}}</td>
                                @else
                                    <td>{{$documento->doc_conteudo}}</td>
                                @endif
                                <td>{{$documento->doc_orgao}}</td>
                                <td>{{$documento->doc_data_expedicao}}</td>
                                <td>{{$documento->doc_observacao}}</td>
                                <?php
                                    $botoes =  [
                                          [
                                            'classButton' => 'btn btn-primary btn-sm',
                                            'icon' => 'fa fa-pencil',
                                            'route' => 'geral.pessoas.documentos.edit',
                                            'parameters' => ['id' => $documento->doc_id],
                                            'label' => '',
                                            'method' => 'get'
                                          ],
                                          [
                                              'classButton' => 'btn-delete btn btn-danger btn-sm',
                                              'icon' => 'fa fa-trash',
                                              'route' => 'geral.pessoas.documentos.delete',
                                              'id' => $documento->doc_id,
                                              'label' => '',
                                              'method' => 'post'
                                          ]
                                        ];
                                ?>
                                @if(!is_null($documento->doc_anx_documento))
                                    <?php $botoes[] =  [
                                              'classButton' => 'btn btn-success btn-sm docAnexo',
                                              'icon' => 'fa fa-download',
                                              'route' => 'geral.pessoas.documentos.anexo',
                                              'parameters' => ['id' => $documento->doc_id],
                                              'label' => '',
                                              'method' => 'get'
                                          ];
                                    ?>
                                @endif
                                <td>
                                  {!! ActionButton::grid([
                                    'type' => 'LINE',
                                    'buttons' => $botoes
                                  ]) !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p>Sem documentos para apresentar</p>
                @endif
            <!-- /.card-body -->
            {!! ActionButton::grid([
                'type' => 'LINE',
                'buttons' => [
                  [
                    'classButton' => 'btn btn-primary',
                    'icon' => 'fa fa-plus-square',
                    'route' => 'geral.pessoas.documentos.create',
                    'parameters' => ['id' => $pessoa->pes_id],
                    'label' => ' Novo Documento',
                    'method' => 'get'
                  ],
                ]
            ]) !!}
          </div>
        </div>
        <!-- /.card -->
    </div>
</div>