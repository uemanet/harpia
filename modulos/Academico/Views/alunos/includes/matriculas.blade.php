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
                                                            'classButton' => 'btn btn-primary modal-update-polo',
                                                            'icon' => 'fa fa-pencil',
                                                            'action' => '/academico/matricularalunocurso/edit/' . $matricula->mat_id,
                                                            'label' => ' Atualizar Polo/Grupo',
                                                            'method' => 'get',
                                                            'attributes' => [
                                                                'data-ofc-id' => $matricula->turma->ofertacurso->ofc_id,
                                                                'data-trm-id' => $matricula->mat_trm_id,
                                                                'data-pol-id' => $matricula->mat_pol_id,
                                                                'data-grp-id' => $matricula->mat_grp_id
                                                            ],
                                                        ],
                                                        [
                                                            'classButton' => 'btn btn-primary modal-update-situacao',
                                                            'icon' => 'fa fa-pencil',
                                                            'action' => '/academico/matricularalunocurso/edit/' . $matricula->mat_id,
                                                            'label' => 'Alterar Situação de Matricula',
                                                            'method' => 'get',
                                                            'attributes' => [
                                                                'data-mat-id' => $matricula->mat_id
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
                            <!-- Modal Mudança Polo/Grupo -->
                            <div class="modal fade modalUpdatePolo">
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

                            <!-- Modal Alteracao Situacao Matrícula -->
                            <div class="modal fade modalUpdateSituacao">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Alterar situação da matrícula</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" id="mat_id" value="">
                                                    {!! Form::label('situacao', 'Situação*', ['class' => 'control-label']) !!}
                                                    <div class="controls">
                                                        {!! Form::select('situacao', $situacaoArray, old('situacao'), ['placeholder' => 'Selecione uma opção', 'class' => 'form-control', 'id' => 'situacao-select'.$matricula->mat_id ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                                                </div>
                                                <div class="form-group col-md-6 text-right">
                                                    <button type="button" class="btn btn-primary btnSituacao">Salvar alterações</button>
                                                </div>
                                            </div>
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
@section('scripts')
    <script type="text/javascript">
        $(function () {

            $('.modal-update-situacao').on("click", function (event) {
                event.preventDefault();

                var matricula = $(this).attr('data-mat-id');

                $('.modalUpdateSituacao').find('#mat_id').val(matricula);

                $('.modalUpdateSituacao').modal();
            });

            $('.btnSituacao').on("click", function () {

                var matricula = $('#mat_id').val();
                var situacao = $('#situacao-select' + matricula).val();
                var token = "{{ csrf_token() }}";

                dados = {
                    id: matricula,
                    situacao: situacao,
                    _token: token
                };

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/matricula/alterarsituacao',
                    data: dados,
                    success: function (response) {
                        // $.harpia.hideloading();
                        toastr.success('Situação da matrícula alterada com sucesso', null, {progressBar: true});
                        location.reload(true);
                    },
                    error: function (response) {
                        toastr.error('Não foi possível alterar a situação da matrícula', null);
                    }
                });
            });

            $('.modal-update-polo').click(function (event) {
                event.preventDefault();

                var action = $(this).attr('href');
                var ofertaCursoId = $(this).attr('data-ofc-id');
                var turmaId = $(this).attr('data-trm-id');
                var poloId = $(this).attr('data-pol-id');
                var grupoId = 0;

                if($(this).attr('data-grp-id')) {
                    grupoId = $(this).attr('data-grp-id');
                }

                $('input[name="trm_id"]').val(turmaId);

                $('.formUpdate').attr('action', action);

                $('#mat_pol_id').empty();
                $('#mat_grp_id').empty();

                $.harpia.httpget("{{url('/')}}/academico/async/polos/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        $('#mat_pol_id').append("<option value=''>Selecione um polo</option>");

                        $.each(response, function (key, obj) {
                            var option = "<option value='"+obj.pol_id+"'";
                            if (poloId && obj.pol_id == poloId) {
                                option += " selected";
                            }
                            option += ">"+obj.pol_nome+"</option>";
                            $('#mat_pol_id').append(option);
                        });
                    } else {
                        $('#mat_pol_id').append("<option value=''>Sem polos cadastrados</option>");
                    }
                });

                if(poloId) {
                    loadingSelectGrupos(turmaId, poloId, grupoId);
                }

                $('.modalUpdatePolo').modal();
            });

            $('#mat_pol_id').change(function() {

                var turmaId = $('input[name="trm_id"]').val();
                var poloId = $(this).val();

                loadingSelectGrupos(turmaId, poloId, 0);
            });

            function loadingSelectGrupos(turmaId, poloId, grupoId) {
                $.harpia.httpget("{{url('/')}}/academico/async/grupos/findallbyturmapolo/"+turmaId+"/"+poloId).done(function (response) {
                    $('#mat_grp_id').empty();
                    if(!$.isEmptyObject(response)) {
                        $('#mat_grp_id').append("<option value=''>Selecione um grupo</option>");

                        $.each(response, function (key, obj) {
                            var option = "<option value='"+obj.grp_id+"'";
                            if ((grupoId > 0) && (obj.grp_id == grupoId)) {
                                option += " selected";
                            }
                            option += ">"+obj.grp_nome+"</option>";
                            $('#mat_grp_id').append(option);
                        });
                    } else {
                        $('#mat_grp_id').append("<option value=''>Sem grupos cadastrados</option>");
                    }
                });
            }
        });
    </script>
@endsection
