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
                            @php
                                $situacaoArray = $situacao;
                                unset($situacaoArray[$matricula->mat_situacao]);
                            @endphp
                            <div class="panel box box-success">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse{{$loop->index}}">
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
                                            <div class="col-md-12 col-sm-6 col-xs-3">
                                                <div class="box box-solid">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title">Informações do Curso</h3>
                                                        <div class="box-tools pull-right">
                                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="col-md-4">
                                                            <p><strong>Nível do
                                                                    Curso:</strong> {{ $matricula->turma->ofertacurso->curso->nivelcurso->nvc_nome }}
                                                            </p>
                                                            <p>
                                                                <strong>Modalidade:</strong> {{ $matricula->turma->ofertacurso->modalidade->mdl_nome }}
                                                            </p>
                                                            <p><strong>Modo de Entrada:</strong> {{ $matricula->mat_modo_entrada }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><strong>Oferta de
                                                                    Curso:</strong> {{$matricula->turma->ofertacurso->ofc_ano}}</p>
                                                            <p><strong>Turma:</strong> {{$matricula->turma->trm_nome}}</p>
                                                            <p><strong>Polo:</strong> {{$matricula->polo->pol_nome}}</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>
                                                                <strong>Grupo:</strong> @if($matricula->grupo) {{$matricula->grupo->grp_nome}} @else
                                                                    Sem Grupo @endif</p>
                                                            @if($matricula->mat_situacao == 'concluido')
                                                                <p><strong>Data de
                                                                        Conclusão:</strong> {{ $matricula->mat_data_conclusao }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-6 col-xs-3">
                                                <div class="box box-solid collapsed-box">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title">Histórico de Matrícula</h3>
                                                        <div class="box-tools pull-right">
                                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="box-body">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th width="20%">Tipo</th>
                                                                <th width="15%">Data</th>
                                                                <th>Observação</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>Matrícula no Curso</td>
                                                                <td>{{ Format::formatDate($matricula->created_at, 'd/m/Y') }}</td>
                                                                <td></td>
                                                            </tr>
                                                            @foreach($matricula->historico as $historico)
                                                                <tr>
                                                                    <td>{{ $historico->hmt_tipo }}</td>
                                                                    <td>{{ Format::formatDate($historico->hmt_data, 'd/m/Y') }}</td>
                                                                    <td>{{ $historico->hmt_observacao }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @if($matricula->mat_situacao != 'concluido')
                                                <div class="row">
                                                    <div class="col-md-12" style="margin-left: 1%;">
                                                        {!! ActionButton::grid([
                                                            'type' => 'LINE',
                                                            'buttons' => [
                                                            [
                                                            'classButton' => 'btn btn-primary modal-update-polo',
                                                            'icon' => 'fa fa-pencil',
                                                            'route' => 'academico.matricularalunocurso.edit',
                                                            'parameters' => $matricula->mat_id,
                                                            'label' => ' Atualizar Polo/Grupo',
                                                            'method' => 'get',
                                                            'attributes' => [
                                                            'data-mat-id' => $matricula->mat_id,
                                                            'data-ofc-id' => $matricula->turma->ofertacurso->ofc_id,
                                                            'data-trm-id' => $matricula->mat_trm_id,
                                                            'data-pol-id' => $matricula->mat_pol_id,
                                                            'data-grp-id' => $matricula->mat_grp_id,
                                                            'data-content' => $loop->index,
                                                            ],
                                                            ],
                                                            [
                                                            'classButton' => 'btn btn-primary modalButton',
                                                            'icon' => 'fa fa-pencil',
                                                            'route' => 'academico.matricularalunocurso.edit',
                                                            'parameters' => $matricula->mat_id,
                                                            'label' => 'Atualizar situação de Matricula',
                                                            'method' => 'get',
                                                            'attributes' => [
                                                            'data-content' => $loop->index,
                                                            'value' => $matricula->mat_id
                                                            ],
                                                            ]
                                                            ]
                                                            ]) !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Alterar Situacao Matricula  -->
                            <div class="modal" id="matricula-modal{{$loop->index}}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Atualizar situação da matrícula</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    {!! Form::label('situacao', 'Situação*', ['class' => 'control-label']) !!}
                                                    <div class="controls">
                                                        {!! Form::select('situacao', $situacaoArray, array_shift($situacaoArray), ['placeholder' => 'Selecione uma opção', 'class' => 'form-control', 'id' => 'situacao-select'.$loop->index ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    {!! Form::label('observacao_situacao', 'Observação', ['class' => 'control-label']) !!}
                                                    <div class="controls">
                                                        {!! Form::text('observacao_situacao', null, ['class' => 'form-control', 'id' => 'observacao_situacao'.$loop->index ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <button type="button" class="btn btn-default pull-left"
                                                            data-dismiss="modal">Cancelar
                                                    </button>
                                                </div>
                                                <div class="form-group col-md-6 text-right">
                                                    <button type="button" class="btn btn-primary modalSave">
                                                        Atualizar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Mudança Polo/Grupo -->
                            <div class="modal fade modalUpdatePolo{{$loop->index}}">
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
                                                {!! Form::hidden('trm_id' . $loop->index, '') !!}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {!! Form::label('mat_pol_id' . $loop->index, 'Polo*') !!}
                                                            {!! Form::select('mat_pol_id' . $loop->index, [], null, ['class' => 'form-control poloSelect']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {!! Form::label('mat_grp_id' . $loop->index, 'Grupo') !!}
                                                            {!! Form::select('mat_grp_id' . $loop->index, [], null, ['class' => 'form-control']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        {!! Form::label('observacao_pologrupo', 'Observação', ['class' => 'control-label']) !!}
                                                        <div class="controls">
                                                            {!! Form::text('observacao_pologrupo', null, ['class' => 'form-control', 'id' => 'observacao_pologrupo'.$loop->index ]) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <button type="button" class="btn btn-default pull-left"
                                                                data-dismiss="modal">Cancelar
                                                        </button>
                                                    </div>
                                                    <div class="form-group col-md-6 text-right">
                                                        <button type="submit" class="btn btn-primary btnAtualizar">
                                                            Atualizar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
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
                {!!
                ActionButton::grid([
                    'type' => 'LINE',
                    'buttons' => [
                    [
                        'classButton' => 'btn btn-primary',
                        'icon' => 'fa fa-plus-square',
                        'route' => 'academico.matricularalunocurso.create',
                        'parameters' => $aluno->alu_id,
                        'label' => ' Nova Matrícula',
                        'method' => 'get'
                    ],
                    ]
                    ])

                 !!}
            </div>
            <!-- /.box-footer -->
        </div>
    </div>
</div>
@section('scripts')
    <script type="text/javascript">

        // Alteracao de situacao de matricula
        $(document).ready(function () {
            $('.modalButton').on("click", function (event) {
                event.preventDefault();

                window.buttonGroup = $(this);
                var modal = $(this).attr("data-content");

                $('#matricula-modal' + modal).modal();

                $('.modalSave').on("click", function (event) {
                    event.preventDefault();

                    var situacao = $('#situacao-select' + modal).val();

                    if (situacao.length === 0) {
                        sweetAlert("Oops...", "Selecione uma opção", "error");
                        return;
                    }

                    var confirmCallback = function (isConfirm) {
                        if (isConfirm) {
                            var matricula = window.buttonGroup.attr("value");
                            var token = "{{ csrf_token() }}";
                            var observacao = $('#observacao_situacao' + modal).val();

                            data = {
                                id: matricula,
                                situacao: situacao,
                                observacao: observacao,
                                _token: token
                            };

                            result = $.harpia.httppost('/academico/async/matricula/alterarsituacao', data);
                            location.reload(true);
                        }
                    };

                    swal({
                        title: "Tem certeza que deseja alterar o status do aluno ?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, alterar status!",
                        cancelButtonText: "Não, quero cancelar!",
                        closeOnConfirm: true
                    }, confirmCallback);
                })
            })
        });

        // Alteracao de polo e grupo
        $(function () {
            $('.modal-update-polo').click(function (event) {
                event.preventDefault();

                window.modalId = $(this).attr("data-content");
                window.turmaId = $(this).attr('data-trm-id');
                window.matricula = $(this).attr('data-mat-id');
                var action = $(this).attr('href');
                var ofertaCursoId = $(this).attr('data-ofc-id');
                var poloId = $(this).attr('data-pol-id');
                var grupoId = 0;

                if ($(this).attr('data-grp-id')) {
                    grupoId = $(this).attr('data-grp-id');
                }


                $('#trm_id' + window.modalId).val(window.turmaId);

                $('.formUpdate').attr('action', action);

                $('#mat_pol_id' + window.modalId).empty();
                $('#mat_grp_id' + window.modalId).empty();

                $.harpia.httpget("{{url('/')}}/academico/async/polos/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        $('#mat_pol_id' + window.modalId).append("<option value=''>Selecione um polo</option>");

                        $.each(response, function (key, obj) {
                            var option = "<option value='" + obj.pol_id + "'";
                            if (poloId && obj.pol_id == poloId) {
                                option += " selected";
                            }
                            option += ">" + obj.pol_nome + "</option>";
                            $('#mat_pol_id' + window.modalId).append(option);
                        });
                    } else {
                        $('#mat_pol_id' + window.modalId).append("<option value=''>Sem polos cadastrados</option>");
                    }
                });

                if (poloId) {
                    loadingSelectGrupos(turmaId, poloId, grupoId);
                }

                $('.modalUpdatePolo' + window.modalId).modal();
            });

            $('.poloSelect').change(function () {

                var turma = window.turmaId;
                var poloId = $(this).val();

                if (poloId) {
                    loadingSelectGrupos(turma, poloId, 0);
                }
                if (!poloId) {
                    $('#mat_grp_id' + window.modalId).empty();
                }
            });

            function loadingSelectGrupos(turmaId, poloId, grupoId) {
                $.harpia.httpget("{{url('/')}}/academico/async/grupos/findallbyturmapolo/" + turmaId + "/" + poloId).done(function (response) {
                    $('#mat_grp_id' + window.modalId).empty();
                    if (!$.isEmptyObject(response)) {
                        $('#mat_grp_id' + window.modalId).append("<option value=''>Selecione um grupo</option>");

                        $.each(response, function (key, obj) {
                            var option = "<option value='" + obj.grp_id + "'";
                            if ((grupoId > 0) && (obj.grp_id == grupoId)) {
                                option += " selected";
                            }
                            option += ">" + obj.grp_nome + "</option>";
                            $('#mat_grp_id' + window.modalId).append(option);
                        });
                    } else {
                        $('#mat_grp_id' + window.modalId).append("<option value=''>Sem grupos cadastrados</option>");
                    }
                });
            }

            $('.btnAtualizar').on("click", function (event) {
                event.preventDefault();

                polo = $('#mat_pol_id' + window.modalId).val();
                grupo = $('#mat_grp_id' + window.modalId).val();

                if (polo.length === 0) {
                    sweetAlert("Oops...", "Selecione um polo", "error");
                    return;
                }

                var confirmCallback = function (isConfirm) {
                    if (isConfirm) {
                        var token = "{{ csrf_token() }}";
                        var observacao = $('#observacao_pologrupo' + window.modalId).val();

                        data = {
                            method: "PUT",
                            mat_pol_id: polo,
                            mat_grp_id: grupo,
                            observacao: observacao,
                            _token: token
                        };

                        result = $.ajax({
                            url: '/academico/matricularalunocurso/edit/' + window.matricula,
                            type: "PUT",
                            data: data,
                            success: function (resp) {
                                $.harpia.hideloading();
                                result = resp;
                            },

                            error: function (e) {
                                $.harpia.hideloading();
                                sweetAlert("Oops...", "Algo estranho aconteceu! Se o problema persistir, entre em contato com a administração do sistema.", "error");
                                result = false;
                            }
                        });

                        location.reload(true);
                    }
                };

                swal({
                    title: "Tem certeza que deseja alterar o polo / grupo do aluno ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, alterar polo / grupo!",
                    cancelButtonText: "Não, quero cancelar!",
                    closeOnConfirm: true
                }, confirmCallback);
            });
        });
    </script>
@endsection
