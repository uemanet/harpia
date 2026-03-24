<!-- Matriculas -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Cursos Matriculados</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(!$aluno->matriculas->isEmpty())
                    <div class="accordion" id="accordionMatriculas">
                        @foreach($aluno->matriculas as $matricula)
                            @php
                                $situacaoArray = $situacao;
                                unset($situacaoArray[$matricula->mat_situacao]);
                            @endphp
                            <div class="accordion-item shadow-sm mb-3 border">
                                <h2 class="accordion-header" id="heading{{$loop->index}}">
                                    <button class="accordion-button @if(!$loop->first) collapsed @endif fw-bold d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$loop->index}}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{$loop->index}}">
                                        <i class="fa fa-graduation-cap me-2 text-primary"></i>
                                        <span class="flex-grow-1">{{ $matricula->turma->ofertacurso->curso->crs_nome }}</span>

                                        @if($matricula->mat_situacao == 'cursando')
                                            <span class="badge bg-info ms-auto me-3">Cursando</span>
                                        @elseif($matricula->mat_situacao == 'reprovado')
                                            <span class="badge bg-danger ms-auto me-3">Reprovado</span>
                                        @elseif($matricula->mat_situacao == 'concluido')
                                            <span class="badge bg-success ms-auto me-3">Concluído</span>
                                        @else
                                            <span class="badge bg-warning ms-auto me-3">{{ucfirst($matricula->mat_situacao)}}</span>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapse{{$loop->index}}" class="accordion-collapse collapse @if($loop->first) show @endif" aria-labelledby="heading{{$loop->index}}" data-bs-parent="#accordionMatriculas">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 py-2">
                                                <div class="card card-solid">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Informações do Curso</h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i class="fa fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <p>
                                                                    <strong>Nível do Curso:</strong> {{ $matricula->turma->ofertacurso->curso->nivelcurso->nvc_nome }}
                                                                </p>
                                                                <p>
                                                                    <strong>Modalidade:</strong> {{ $matricula->turma->ofertacurso->modalidade->mdl_nome }}
                                                                </p>
                                                                <p>
                                                                    <strong>Modo de Entrada:</strong> {{ $matricula->mat_modo_entrada }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <p>
                                                                    <strong>Oferta de Curso:</strong> {{$matricula->turma->ofertacurso->ofc_ano}}
                                                                </p>
                                                                <p>
                                                                    <strong>Turma:</strong> {{$matricula->turma->trm_nome}}
                                                                </p>
                                                                <p>
                                                                    <strong>Polo:</strong> {{$matricula->polo->pol_nome}}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <p>
                                                                    <strong>Grupo:</strong>
                                                                    @if($matricula->grupo)
                                                                        {{$matricula->grupo->grp_nome}}
                                                                    @else
                                                                        Sem Grupo
                                                                    @endif
                                                                </p>
                                                                @if($matricula->mat_situacao == 'concluido')
                                                                    <p>
                                                                        <strong>Data de Conclusão:</strong> {{ $matricula->mat_data_conclusao }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 py-2">
                                                <div class="card card-solid">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Histórico de Matrícula</h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i class="fa fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
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
                                                                    ],
                                                                    [
                                                                        'classButton' => 'btn btn-delete btn-danger',
                                                                        'icon' => 'fa fa-trash',
                                                                        'route' => 'academico.matricularalunocurso.delete',
                                                                        'id' => $matricula->mat_id,
                                                                        'label' => 'Desmatricular aluno',
                                                                        'method' => 'post'
                                                                    ]
                                                                ]
                                                            ])
                                                        !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Alterar Situacao Matricula  -->
                            <div class="modal fade" id="matricula-modal{{$loop->index}}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Atualizar situação da matrícula</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="situacao" class="form-label">Situação <small class="obrigatorio-dot">*</small></label>
                                                    <div class="controls">
                                                        <select name="situacao" class="form-control" id="situacao-select{{$loop->index}}">
                                                            <option value="">Selecione uma opção</option>
                                                            @foreach($situacaoArray as $key => $value)
                                                                <option value="{{ $key }}" {{ array_shift($situacaoArray) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="observacao_situacao{{$loop->index}}" class="form-label">Observação</label>
                                                    <div class="controls">
                                                        <input type="text" name="observacao_situacao" class="form-control" id="observacao_situacao{{$loop->index}}" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="form-group col-md-6">
                                                    <button type="button" class="btn btn-default float-start" data-bs-dismiss="modal">Cancelar</button>
                                                </div>
                                                <div class="form-group col-md-6 text-end">
                                                    <button type="button" class="btn btn-primary modalSave">Atualizar</button>
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
                                            <h4 class="modal-title">Atualizar Polo/Grupo</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="formUpdate" action="" method="POST">
                                                <input name="_method" type="hidden" value="PUT">
                                                {{csrf_field()}}
                                                <input type="hidden" name="'trm_id' . $loop->index" id="trm_id{{$loop->index}}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mat_pol_id{{$loop->index}}">Polo <small class="obrigatorio-dot">*</small></label>
                                                            <select name="'mat_pol_id' . $loop->index" id="mat_pol_id{{$loop->index}}" class="form-control poloSelect"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mat_grp_id{{$loop->index}}">Grupo</label>
                                                            <select name="'mat_grp_id' . $loop->index" id="mat_grp_id{{$loop->index}}" class="form-control"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="observacao_pologrupo{{$loop->index}}" class="form-label">Observação</label>
                                                        <div class="controls">
                                                            <input type="text" name="observacao_pologrupo" class="form-control" id="observacao_pologrupo{{$loop->index}}" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="form-group col-md-6">
                                                        <button type="button" class="btn btn-default float-start" data-bs-dismiss="modal">Cancelar</button>
                                                    </div>
                                                    <div class="form-group col-md-6 text-end">
                                                        <button type="submit" class="btn btn-primary btnAtualizar">Atualizar</button>
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
            <!-- /.card-body -->
            <div class="card-footer">
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
            <!-- /.card-footer -->
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

                $('#matricula-modal' + modal).modal('show');

                $('.modalSave').off("click").on("click", function (event) {
                    event.preventDefault();

                    var situacao = $('#situacao-select' + modal).val();

                    if (situacao.length === 0) {
                        Swal.fire("Oops...", "Selecione uma opção", "error");
                        return;
                    }

                    var confirmCallback = function () {
                        var matricula = window.buttonGroup.attr("value");
                        var token = "{{ csrf_token() }}";
                        var observacao = $('#observacao_situacao' + modal).val();

                        var data = {
                            id: matricula,
                            situacao: situacao,
                            observacao: observacao,
                            _token: token
                        };

                        result = $.harpia.httppost('/academico/async/matricula/alterarsituacao', data);
                        location.reload(true);
                    };

                    Swal.fire({
                        title: "Tem certeza que deseja alterar o status do aluno ?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#dc3545",
                        cancelButtonColor: "#6c757d",
                        confirmButtonText: "Sim, alterar status!",
                        cancelButtonText: "Não, quero cancelar!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            confirmCallback();
                        }
                    });
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
                    loadingSelectGrupos(window.turmaId, poloId, grupoId);
                }

                $('.modalUpdatePolo' + window.modalId).modal('show');
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

                var polo = $('#mat_pol_id' + window.modalId).val();
                var grupo = $('#mat_grp_id' + window.modalId).val();

                if (polo.length === 0) {
                    Swal.fire("Oops...", "Selecione um polo", "error");
                    return;
                }

                var confirmCallback = function () {
                    var token = "{{ csrf_token() }}";
                    var observacao = $('#observacao_pologrupo' + window.modalId).val();

                    var data = {
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
                            Swal.fire("Oops...", "Algo estranho aconteceu! Se o problema persistir, entre em contato com a administração do sistema.", "error");
                            result = false;
                        }
                    });

                    location.reload(true);
                };

                Swal.fire({
                    title: "Tem certeza que deseja alterar o polo / grupo do aluno ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Sim, alterar polo / grupo!",
                    cancelButtonText: "Não, quero cancelar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        confirmCallback();
                    }
                });
            });
        });
    </script>
@endsection