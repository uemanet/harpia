<!-- Titulações -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title">Titulações</h3>

                <div class="card-tools pull-right">
                    <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(!$pessoa->titulacoes_informacoes->isEmpty())
                    <table class="table table-bordered">
                        <tr>
                            <th>Titulação</th>
                            <th>Título/Curso</th>
                            <th>Instituição</th>
                            <th>Ano de Início</th>
                            <th>Ano de Conclusão</th>
                            <th>Ações</th>
                        </tr>
                        @foreach($pessoa->titulacoes_informacoes as $titulacao)
                            <tr>
                                <td>{{$titulacao->titulacao->tit_nome}}</td>
                                <td>{{$titulacao->tin_titulo}}</td>
                                <td>{{$titulacao->tin_instituicao}}</td>
                                <td>{{($titulacao->tin_anoinicio)}}</td>
                                <td>{{($titulacao->tin_anofim)}}</td>
                                <td>

                                    {!! ActionButton::grid([
                                         'type' => 'LINE',
                                         'buttons' => [
                            [
                                'classButton' => 'btn btn-primary btn-sm',
                                'icon' => 'fa fa-pencil',
                                'route' => 'geral.pessoas.titulacoesinformacoes.edit',
                                'parameters' => ['id' => $titulacao->tin_id],
                                'label' => '',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete btn btn-danger btn-sm',
                                'icon' => 'fa fa-trash',
                                'route' => 'geral.pessoas.titulacoesinformacoes.delete',
                                'id' => $titulacao->tin_id,
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
                    <p>Sem titulações para apresentar</p>
                @endif
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                {!! ActionButton::grid([
                    'type' => 'LINE',
                    'buttons' => [
                        [
                            'classButton' => 'btn btn-primary',
                            'icon' => 'fa fa-plus-square',
                            'route' => 'geral.pessoas.titulacoesinformacoes.create',
                            'parameters' => ['id' => $pessoa->pes_id],
                            'label' => ' Nova Titulação',
                            'method' => 'get'
                        ],
                    ]
                ]) !!}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>