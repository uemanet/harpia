<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Disciplinas Não Ofertadas</h3>

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
                @if($disciplinas->count())
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th width="10%">Carga Horária</th>
                            <th width="5%">Créditos</th>
                            <th width="12%">Tipo de Disciplina</th>
                            <th width="12%">Tipo de Avaliação</th>
                            <th width="10%">Vagas</th>
                            <th width="25%">Professor</th>
                            <th width="8%"></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($disciplinas as $disciplina)
                                <tr>
                                    <td>{{ $disciplina->dis_nome }}</td>
                                    <td>{{ $disciplina->dis_carga_horaria }} horas</td>
                                    <td>{{ $disciplina->dis_creditos }}</td>
                                    <td>{{ $disciplina->mdc_tipo_disciplina }}</td>
                                    <td>
                                        <div class="form-group">
                                            {!! Form::select('ofd_tipo_avaliacao',
                                                ['numerica' => 'Numérica', 'conceitual' => 'Conceitual'], null,
                                                ['class' => 'form-control tipo-avaliacao']
                                                )
                                            !!}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            {!! Form::number('ofd_qtd_vagas',0, ['class' => 'form-control qtd-vagas']) !!}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            {!! Form::select('ofd_prf_id',
                                                $professores, null,
                                                ['class' => 'form-control professor', 'placeholder' => 'Selecione um professor']
                                                )
                                            !!}
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btnAdicionar" data-mdc="{{ $disciplina->mdc_id }}">
                                            <i class="fa fa-save"></i> Ofertar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Não há disciplinas para serem ofertadas para o período requerido</p>
                @endif
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>