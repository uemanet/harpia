<!-- Box Disciplinas Ofertadas -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Disciplinas Ofertadas</h3>

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
                @if(!empty($naomatriculadas))
                    <table class="table table-bordered table-matricular">
                        <thead>
                            <tr>
                                <th width="1%"><label><input type="checkbox" id="select_all"></label></th>
                                <th>Disciplina</th>
                                <th>Turma</th>
                                <th>Carga Horária</th>
                                <th>Créditos</th>
                                <th>Vagas</th>
                                <th>Professor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($naomatriculadas as $naomatriculada)
                                <tr>
                                    @if($naomatriculada->status == 1)
                                        <td><label><input type="checkbox" class="matricular" value="{{ $naomatriculada->ofd_id }}"></label></td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{ $naomatriculada->dis_nome }}</td>
                                    <td>{{ $naomatriculada->trm_nome }}</td>
                                    <td>{{ $naomatriculada->dis_carga_horaria }} horas</td>
                                    <td>{{ $naomatriculada->dis_creditos }}</td>
                                    <td>{{ $naomatriculada->quant_matriculas }}/<strong>{{ $naomatriculada->ofd_qtd_vagas }}</strong></td>
                                    <td>{{ $naomatriculada->pes_nome }}</td>
                                    <td>
                                        @if($naomatriculada->status == 1)
                                            <span class="label label-success">Disponível</span>
                                        @elseif($naomatriculada->status == 2)
                                            <span class="label label-warning">Pré-requisitos não satisfeitos</span>
                                        @else
                                            <span class="label label-danger">Sem vagas disponíveis</span>
                                        @endif
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

<!-- Box Disciplinas Matriculadas -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Disciplinas Matriculadas</h3>

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
                @if($matriculadas->count())
                    <table class="table table-bordered table-desmatricular">
                    <thead>
                        <tr>
                            <th width="1%"><label><input type="checkbox" id="select_all_desmatricular"></label></th>
                            <th>Disciplina</th>
                            <th>Turma</th>
                            <th>Carga Horária</th>
                            <th>Créditos</th>
                            <th>Vagas</th>
                            <th>Professor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matriculadas as $matriculada)
                            <tr>
                                @if($matriculada->status == 1)
                                    <td><label><input type="checkbox" class="desmatricular" value="{{ $matriculada->ofd_id }}"></label></td>
                                @else
                                    <td></td>
                                @endif
                                <td>{{ $matriculada->dis_nome }}</td>
                                <td>{{ $matriculada->trm_nome }}</td>
                                <td>{{ $matriculada->dis_carga_horaria }} horas</td>
                                <td>{{ $matriculada->dis_creditos }}</td>
                                <td>{{ $matriculada->quant_matriculas }}/<strong>{{ $matriculada->ofd_qtd_vagas }}</strong></td>
                                <td>{{ $matriculada->pes_nome }}</td>
                                @if($matriculada->mof_situacao_matricula == 'cursando')
                                    <td><span class="label label-info">Cursando</span></td>
                                @elseif($matriculada->mof_situacao_matricula == 'cancelado')
                                    <td><span class="label label-warning">Cancelado</span></td>
                                @elseif($matriculada->mof_situacao_matricula == 'aprovado_media')
                                    <td><span class="label label-success">Aprovado Por Média</span></td>
                                @elseif($matriculada->mof_situacao_matricula == 'aprovado_final')
                                    <td><span class="label label-success">Aprovado Por Final</span></td>
                                @elseif($matriculada->mof_situacao_matricula == 'reprovado_final')
                                    <td><span class="label label-danger">Reprovado Por Final</span></td>
                                @elseif($matriculada->mof_situacao_matricula == 'reprovado_media')
                                    <td><span class="label label-danger">Reprovado Por Média</span></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="form-group">
                    <button class="btn btn-danger pull-right hidden" id="confirmDesmatricular">Desmatricular Aluno</button>
                </div>
                @else
                    <p>Aluno não está matriculado em disciplinas deste período</p>
                @endif
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>
