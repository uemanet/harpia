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
                                    <td>{{ $disciplina->per_nome }}</td>
                                    <td>{{ $disciplina->dis_nome }}</td>
                                    <td>{{ $disciplina->dis_carga_horaria }} horas</td>
                                    <td>{{ $disciplina->dis_creditos }}</td>
                                    <td>
                                        {!! ActionButton::grid([
                                                'type' => 'LINE',
                                                'buttons' => [
                                                    [
                                                        'classButton' => 'btn btn-primary modalButton',
                                                        'icon' => 'fa fa-paperclip',
                                                        'route' => 'academico.aproveitamentoestudos.aproveitardisciplina',
                                                        'parameters' => $disciplina->ofd_id,
                                                        'label' => '',
                                                        'method' => 'get'
                                                    ]
                                                ]
                                            ])
                                         !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="form-group">
                        <button class="btn btn-primary pull-right hidden" id="confirmMatricula">Confirmar Matricula</button>
                    </div>
                @else
                    <p>Não há disciplinas disponíveis para este período</p>
                @endif
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>
