<!-- Matriculas -->
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Cursos Matriculados</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(!$aluno->matriculas->isEmpty())
                    <div class="box-group" id="accordion">
                        @foreach($aluno->matriculas as $matricula)
                            <div class="panel box box-success">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$loop->index}}">
                                            {{ $matricula->turma->ofertacurso->curso->crs_nome }}
                                        </a>
                                    </h4>
                                    @if($matricula->mat_situacao == 'cursando')
                                        <span class="label label-info pull-right">Cursando</span>
                                    @elseif($matricula->mat_situacao == 'reprovado')
                                        <span class="label label-danger pull-right">Reprovado</span>
                                    @elseif($matricula->mat_situacao == 'concluido')
                                        <span class="label label-success pull-right">Concluído</span>
                                    @else
                                        <span class="label label-warning pull-right">{{ucfirst($matricula->mat_situacao)}}</span>
                                    @endif
                                </div>
                                <div class="panel-collapse collapse" id="collapse{{ $loop->index }}">
                                    <div class="box-body">
                                        @if(!empty($gradesCurriculares[$matricula->mat_id]) && !empty($gradesCurriculares[$matricula->mat_id]['modulos']))
                                            <div class="box-group" id="accordion">
                                                @foreach($gradesCurriculares[$matricula->mat_id]['modulos'] as $modulo)
                                                    <div class="panel box">
                                                        <div class="box-header with-border">
                                                            <h4 class="box-title">
                                                                <a data-toggle="collapse" data-parent="#accordion" href="#modulo{{$loop->index}}">
                                                                    {{$modulo['mdo_nome']}}
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div class="panel-collapse collapse" id="modulo{{$loop->index}}">
                                                            <div class="box-body">
                                                                <div class="col-md-8">
                                                                    @if(!empty($modulo['ofertas_disciplinas']))
                                                                        <table class="table table-bordered">
                                                                            <tbody>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Disciplina</th>
                                                                            </tr>
                                                                            @foreach ($modulo['ofertas_disciplinas'] as $oferta)
                                                                                <tr>
                                                                                    <td>{{$oferta->mdc_id}}</td>
                                                                                    <td>{{$oferta->dis_nome}}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Aluno não possui nenhuma matrícula</p>
                @endif
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>