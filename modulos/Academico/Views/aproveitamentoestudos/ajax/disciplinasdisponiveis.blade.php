<!-- Box Disciplinas Ofertadas -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Disciplinas Disponiveis para aproveitamento</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
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
                                        {!! ActionButton::grid([
                                                'type' => 'LINE',
                                                'buttons' => [
                                                    [
                                                        'classButton' => 'btn btn-success modalButton',
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
    <!-- /.box-body -->
</div>

<!-- Box Disciplinas já aproveitadas pelo aluno -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Disciplinas Aproveitadas</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
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
    <!-- /.box-body -->
</div>