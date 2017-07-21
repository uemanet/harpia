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
                @if($ofertas->count())
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th width="10%">Carga Horária</th>
                            <th width="5%">Créditos</th>
                            <th width="12%">Tipo de Avaliação</th>
                            <th width="12%">Tipo de Disciplina</th>
                            <th width="5%">Vagas</th>
                            <th>Professor</th>
                            @if($buttonDelete)
                                <th width="5%">Ações</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ofertas as $oferta)
                            <tr>
                                <td>{{ $oferta->moduloDisciplina->disciplina->dis_nome }}</td>
                                <td>{{ $oferta->moduloDisciplina->disciplina->dis_carga_horaria }} horas</td>
                                <td>{{ $oferta->moduloDisciplina->disciplina->dis_creditos }}</td>
                                <td>{{ $oferta->ofd_tipo_avaliacao }}</td>
                                <td>{{ $oferta->moduloDisciplina->mdc_tipo_disciplina }}</td>
                                <td>{{ $oferta->ofd_quant_matriculados }}/<strong>{{ $oferta->ofd_qtd_vagas }}</strong></td>
                                <td>{{ $oferta->professor->pessoa->pes_nome }}</td>
                                @if($buttonDelete)
                                    @if(!$oferta->ofd_quant_matriculados)
                                        <td>
                                            <form action="">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="POST">
                                                <input type="hidden" name="id" value="{{ $oferta->ofd_id }}">
                                                <button class="btn-delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>Não há disciplinas ofertadas para o período requerido</p>
                @endif
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>