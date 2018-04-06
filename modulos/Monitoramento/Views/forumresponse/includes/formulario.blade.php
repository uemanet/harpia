<div class="row">
    <div class="form-group col-md-3">
        {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
        {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Escolha um curso']) !!}
    </div>
    <div class="form-group col-md-3">
        {!! Form::label('ofc_id', 'Oferta do Curso*', ['class' => 'control-label']) !!}
        {!! Form::select('ofc_id', [], null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-3">
        {!! Form::label('trm_id', 'Turma*', ['class' => 'control-label']) !!}
        {!! Form::select('trm_id', [], null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-3">
        {!! Form::label('grp_id', 'Tipo de Tutoria*', ['class' => 'control-label']) !!}
        {!! Form::select('grp_id', [], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        {!! Form::label('tut_id', 'Tutor*', ['class' => 'control-label']) !!}
        {!! Form::select('tut_id', [], null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-1">
        <label for="" class="control-label"></label>
        <button class="btn btn-primary form-control" id="btnLocalizar"><i class="fa fa-search"></i></button>
    </div>
</div>

@section('scripts')


    <script type="application/javascript">
        $(document).ready(function () {
            $('#crs_id').prop('selectedIndex', 0);
        });
    </script>
    <script type="application/javascript">

        $('#crs_id').change(function (e) {
            var crsId = $(this).val();

            var selectOfertas = $('#ofc_id');
            var selectTurmas = $('#trm_id');
            var selectTutores = $('#tut_id');

            if (crsId) {

                // Populando o select de ofertas de cursos
                selectOfertas.empty();
                selectTurmas.empty();
                selectTutores.empty();

                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + crsId)
                    .done(function (data) {
                        if (!$.isEmptyObject(data)) {
                            selectOfertas.append("<option>Selecione a oferta</option>");
                            $.each(data, function (key, value) {
                                selectOfertas.append('<option value="' + value.ofc_id + '">' + value.ofc_ano + '</option>');
                            });
                        } else {
                            selectOfertas.append("<option>Sem ofertas cadastradas</option>");

                        }
                    });
            }
        });

        $('#ofc_id').change(function (e) {
            var ofertaId = $(this).val();

            var selectTurmas = $('#trm_id');
            var selectTutores = $('#tut_id');

            if (ofertaId) {
                selectTurmas.empty();
                selectTutores.empty();

                $.harpia.httpget('{{url("/")}}/academico/async/turmas/findallbyofertacursointegrada/' + ofertaId)
                    .done(function (data) {
                        if (!$.isEmptyObject(data)) {
                            selectTurmas.append('<option>Selecione a turma</option>');
                            $.each(data, function (key, obj) {
                                selectTurmas.append('<option value="' + obj.trm_id + '">' + obj.trm_nome + '</option>')
                            });
                        } else {
                            selectTurmas.append('<option>Sem turmas cadastradas</option>')
                        }
                    });
            }

        })

        $('#trm_id').change(function (e) {
            var turmaId = $(this).val();
            var selectGrupos = $('#grp_id');
            var selectTutores = $('#tut_id');

            if (turmaId) {
                selectGrupos.empty();
                selectTutores.empty();
                selectGrupos.append('<option>Selecione o tipo de tutoria</option>');
                selectGrupos.append('<option value="presencial">Presencial</option>')
                selectGrupos.append('<option value="distancia">Distância</option>')

            }

        })

        $('#grp_id').change(function (e) {
            var turmaId = $('#trm_id').val();
            var tipotutoria = $(this).val();
            var selectTutores = $('#tut_id');

            if (turmaId) {
                selectTutores.empty();
                $.harpia.httpget('{{url("/")}}/academico/async/tutores/findallbyturmatipotutoria/' + turmaId + '/' + tipotutoria)
                    .done(function (data) {
                        if (!$.isEmptyObject(data)) {
                            selectTutores.append('<option>Selecione o tutor</option>');
                            $.each(data, function (key, obj) {
                                selectTutores.append('<option value="' + obj.pes_id + '">' + obj.pes_nome + '</option>')
                            });
                        } else {
                            selectTutores.append('<option value = "" >Sem tutores cadastrados nessa turma</option>')
                        }
                    });
            }

        })

    </script>
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });

        $(document).on('click', '#btnLocalizar', function () {
            event.preventDefault();

            var token = '{{$monitoramento->asr_token}}';
            var moodlewsformat = "json";
            var wsfunction = "local_monitor_tutor_answers";
            var url = "{{$ambiente->amb_url}}";
            var turmaId = $('#trm_id').val();
            var tutorId = $('#tut_id').val();

            if (!tutorId) {
                return false;
            }
            $.harpia.showloading();
            var request = $.ajax({
                url: url + "webservice/rest/server.php?wstoken=" + token + "&wsfunction=" + wsfunction + "&pesid=" + tutorId + "&trmid=" + turmaId + "&moodlewsrestformat=" + moodlewsformat,
                type: "POST",
                dataType: "json",
                async: true,
                success: function (moodledata) {

                    if (moodledata.exception) {
                        toastr.error(moodledata.message, null, {progressBar: true});
                    }

                    if (moodledata.hasOwnProperty("itens") && moodledata.itens.length > 0) {
                        renderTable(moodledata);
                    } else if (moodledata.hasOwnProperty("itens")) {
                        showEmptyTable();
                    }
                    $.harpia.hideloading();

                    $('.popover-dismiss').popover({
                        trigger: 'focus'
                    })
                    $(function () {
                        $('[data-toggle="popover"]').popover()
                    })

                },
                error: function (error) {
                    $.harpia.hideloading();

                    toastr.error('Erro ao tentar se comunicar com o Ambiente Virtual.', null, {progressBar: true});
                }
            });
        })

        renderTable = function (moodledata) {

            html = '<div class="row"><div class="col-md-12"><h3>' + moodledata.course + '</h3>';
            html += '</div></div>';

            var grupoatual = moodledata.itens[0].groupid;
            $.each(moodledata.itens, function (chave, objeto) {

                if (grupoatual == objeto.groupid && chave != 0) {
                    return true;

                }
                if (grupoatual != objeto.groupid) {
                    grupoatual = objeto.groupid;

                }
                html += '<div class="row"><div class="col-md-12">';
                html += '<div class="box">';
                html += '<div class="box-header">';
                html += '<h3 class="box-title">Grupo: ' + objeto.groupname + '</h3>';
                html += '</div>';
                html += '<div class="box-body no-padding">';
                html += '<table class="table table-condensed">';
                html += '<tr>';
                html += '<th style="width: 50%">Discussão</th>';
                html += '<th style="width: 15%">Participação ';
                html += '<a tabindex="0" class="badge bg-block" role="button" data-placement="top" data-toggle="popover" data-trigger="focus" title="Participação" data-content="Esta coluna contém a porcentagem total de respostas dos tutores em relação as todas as postagens dos alunos">?</a></th>';
                html += '<th style="width: 15%">Respostas a posts ';
                html += '<a tabindex="0" class="badge bg-block" role="button" data-placement="top" data-toggle="popover" data-trigger="focus" title="Respostas a posts" data-content="Esta coluna contém a porcentagem de respostas em relação aos posts dos alunos referentes a discussão no primeiro nível">?</a></th>';
                html += '<th style="width: 15%">Tempo resposta ';
                html += '<a tabindex="0" class="badge bg-block" role="button" data-placement="top" data-toggle="popover" data-trigger="focus" title="Tempo Médio de respostas" data-content="Esta coluna contém o tempo médio que o tutor demora para responder aos posts de primeiro nível">?</a></th>';
                html += '</tr>';
                $.each(moodledata.itens, function (key, obj) {

                    if (obj.groupid == grupoatual) {
                        html += '<tr>';
                        html += '<td>' + obj.discussion + '</td>';

                        if (obj.userparticipation > 0.7) {
                            html += '<td><span class="badge bg-green">' + (obj.userparticipation * 100).toPrecision(3) + '%</span></td>';
                        } else if (obj.userparticipation > 0.4 && obj.userparticipation < 0.7) {
                            html += '<td><span class="badge bg-yellow">' + (obj.userparticipation * 100).toPrecision(3) + '%</span></td>';
                        } else if (obj.userparticipation > 0.0 && obj.userparticipation <= 0.4) {
                            html += '<td><span class="badge bg-red">' + (obj.userparticipation * 100).toPrecision(3) + '%</span></td>';
                        } else {
                            html += '<td><span class="badge bg-red">0%</span></td>';
                        }

                        if (obj.percent > 0.7) {
                            html += '<td><span class="badge bg-green">' + (obj.percent * 100).toPrecision(3) + '%</span></td>';
                        } else if (obj.percent > 0.4 && obj.percent < 0.7) {
                            html += '<td><span class="badge bg-yellow">' + (obj.percent * 100).toPrecision(3) + '%</span></td>';
                        } else if (obj.percent > 0.0 && obj.percent <= 0.4) {
                            html += '<td><span class="badge bg-red">' + (obj.percent * 100).toPrecision(3) + '%</span></td>';
                        } else {
                            html += '<td><span class="badge bg-red">0%</span></td>';
                        }

                        html += '<td><span class="badge bg-blue">' + obj.mediumresponsetime + '</span></td>';
                        html += '</tr>';
                    }
                });

                html += '</table>';
                html += '</div>';
                html += '</div>';
            });

            $('#boxTutores').removeClass('hidden');
            $('#boxTutores .box-body').empty().append(html);
        }


        showEmptyTable = function () {
            html = '<div class="row"><div class="col-md-12"><h3>Este tutor não está vinculado a nenhum fórum</h3>';
            html += '</div></div>';

            $('#boxTutores').removeClass('hidden');
            $('#boxTutores .box-body').empty().append(html);
        }

    </script>
@stop
