<div class="py-2">
    <!-- Box Disciplinas Ofertadas -->
    <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Disciplinas Disponiveis para aproveitamento</h3>

            <div class="card-tools pull-right">
                <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @if(!empty($disciplinasdisponiveis))
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th> # </th>
                                <th>Período Letivo</th>
                                <th>Disciplina</th>
                                <th>Carga Horária</th>
                                <th>Créditos</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($disciplinasdisponiveis as $disciplina)
                                <tr>
                                    <td>{{ $disciplina->ofd_id }}</td>
                                    <td>{{ $disciplina->per_nome }}</td>
                                    <td>{{ $disciplina->dis_nome }}</td>
                                    <td>{{ $disciplina->dis_carga_horaria }} horas</td>
                                    <td>{{ $disciplina->dis_creditos }}</td>
                                    <td>
{{--                                        TODO: fix modal--}}
                                        {!! ActionButton::grid([
                                                'type' => 'LINE',
                                                'buttons' => [
                                                    [
                                                        'classButton' => 'btn btn-success btnAproveitar',
                                                        'icon' => 'fa fa-plus',
                                                        'route' => 'academico.aproveitamentoestudos.aproveitardisciplina',
                                                        'parameters' => [$disciplina->ofd_id,$disciplina->ofd_id ],
                                                        'label' => '',
                                                        'method' => 'get',
                                                        'attributes' => [
                                                            'data-ofc-id' => $disciplina->ofd_id,
                                                            'data-content' => $loop->index
                                                         ]
                                                    ]
                                                ]
                                            ])
                                         !!}
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Não há disciplinas disponíveis para este período</p>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</div>
<div class="py-2">
    <!-- Box Disciplinas já aproveitadas pelo aluno -->
    <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Disciplinas Aproveitadas</h3>

            <div class="card-tools pull-right">
                <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @if(!empty($disciplinasaproveitadas))
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Período Letivo</th>
                                    <th>Disciplina</th>
                                    <th>Nota</th>
                                    <th>Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($disciplinasaproveitadas as $disciplina)
                                    <tr>

                                        <td width="5%">{{ $disciplina->ofd_id }}</td>
                                        <td width="15%">{{ $disciplina->per_nome }}</td>
                                        <td width="20%">{{ $disciplina->dis_nome }}</td>
                                        <td width="10%">
                                            @if($disciplina->ofd_tipo_avaliacao == 'numerica')
                                                {{ $disciplina->mof_mediafinal }}
                                            @else
                                                {{ $disciplina->mof_conceito }}
                                            @endif
                                        </td>
                                        <td width="40%">
                                            {{ $disciplina->mof_observacao }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Aluno não possui disciplinas aproveitadas nesse período</p>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</div>
