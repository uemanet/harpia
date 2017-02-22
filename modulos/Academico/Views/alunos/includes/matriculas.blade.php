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
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Nível do Curso:</strong> {{ $matricula->turma->ofertacurso->curso->nivelcurso->nvc_nome }}</p>
                                                <p><strong>Modalidade:</strong> {{ $matricula->turma->ofertacurso->modalidade->mdl_nome }}</p>
                                                <p><strong>Modo de Entrada:</strong> {{ $matricula->mat_modo_entrada }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Oferta de Curso:</strong> {{$matricula->turma->ofertacurso->ofc_ano}}</p>
                                                <p><strong>Turma:</strong> {{$matricula->turma->trm_nome}}</p>
                                                <p><strong>Polo:</strong> {{$matricula->polo->pol_nome}}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Grupo:</strong> @if($matricula->grupo) {{$matricula->grupo->grp_nome}} @else Sem Grupo @endif</p>
                                                @if($matricula->mat_situacao == 'concluido')
                                                    <p><strong>Data de Conclusão:</strong> {{ $matricula->mat_data_conclusao }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($matricula->mat_situacao != 'concluido')
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! ActionButton::grid([
                                                    'type' => 'LINE',
                                                    'buttons' => [
                                                        [
                                                            'classButton' => 'btn btn-primary modalUpdate',
                                                            'icon' => 'fa fa-pencil',
                                                            'action' => '/academico/matricularalunocurso/edit/' . $matricula->mat_id,
                                                            'label' => ' Atualizar Polo/Grupo',
                                                            'method' => 'get',
                                                            'attributes' => [
                                                                'data-ofc-id' => $matricula->turma->ofertacurso->ofc_id,
                                                                'data-trm-id' => $matricula->mat_trm_id
                                                            ],
                                                        ],
                                                    ]
                                                ]) !!}
                                            </div>
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
            <div class="box-footer">
                {!! ActionButton::grid([
                    'type' => 'LINE',
                    'buttons' => [
                        [
                            'classButton' => 'btn btn-primary',
                            'icon' => 'fa fa-plus-square',
                            'action' => '/academico/matricularalunocurso/create/' . $aluno->alu_id,
                            'label' => ' Nova Matrícula',
                            'method' => 'get',
                        ],
                    ]
                ]) !!}
            </div>
            <!-- /.box-footer -->
        </div>
    </div>
</div>

<div class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
                <h4 class="modal-title">
                    Atualizar Polo/Grupo
                </h4>
            </div>
            <div class="modal-body">
                <form class="formUpdate" action="" method="POST">
                    <input name="_method" type="hidden" value="PUT">
                    {{csrf_field()}}
                    {!! Form::hidden('trm_id', '') !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('mat_pol_id', 'Polo*') !!}
                                {!! Form::select('mat_pol_id', [], null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('mat_grp_id', 'Grupo') !!}
                                {!! Form::select('mat_grp_id', [], null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Atualizar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
        $(function () {

            $('.modalUpdate').click(function (event) {
                event.preventDefault();

                var action = $(this).attr('href');
                var ofertaCursoId = $(this).attr('data-ofc-id');
                var turmaId = $(this).attr('data-trm-id');

                $('input[name="trm_id"]').val(turmaId);

                $('.formUpdate').attr('action', action);

                $('#mat_pol_id').empty();
                $('#mat_grp_id').empty();

                $.harpia.httpget("{{url('/')}}/academico/async/polos/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        $('#mat_pol_id').append("<option value=''>Selecione um polo</option>");

                        $.each(response, function (key, obj) {
                            $('#mat_pol_id').append("<option value='"+obj.pol_id+"'>"+obj.pol_nome+"</option>");
                        });
                    } else {
                        $('#mat_pol_id').append("<option value=''>Sem polos cadastrados</option>");
                    }
                });

                $('.modal').modal();
            });

            $('#mat_pol_id').change(function() {

                var turmaId = $('input[name="trm_id"]').val();
                var poloId = $(this).val();
                $('#mat_grp_id').empty();

                $.harpia.httpget("{{url('/')}}/academico/async/grupos/findallbyturmapolo/"+turmaId+"/"+poloId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        $('#mat_grp_id').append("<option value=''>Selecione um grupo</option>");

                        $.each(response, function (key, obj) {
                            $('#mat_grp_id').append("<option value='"+obj.grp_id+"'>"+obj.grp_nome+"</option>");
                        });
                    } else {
                        $('#mat_grp_id').append("<option value=''>Sem grupos cadastrados</option>");
                    }
                });
            });
        });
    </script>
@endsection