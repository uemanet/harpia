@extends('layouts.modulos.academico')

@section('title')
    Certificação
@stop

@section('subtitle')
    Gerenciamento de registros
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{url('/')}}/css/plugins/select2.css">
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="form-group col-md-3">
                    {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
                    {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Escolha um curso']) !!}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('ofc_id', 'Oferta*', ['class' => 'control-label']) !!}
                    {{ Form::select('ofc_id', [], null, ['class' => 'form-control', 'id' => 'ofc_id', 'value' => Input::get('ofc_id'), 'placeholder' => 'Oferta']) }}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('trm_id', 'Turma*', ['class' => 'control-label']) !!}
                    {{ Form::select('trm_id', [], null, ['class' => 'form-control', 'id' => 'trm_id', 'value' => Input::get('trm_id'), 'placeholder' => 'Turma']) }}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('mdo_id', 'Módulo*', ['class' => 'control-label']) !!}
                    {{ Form::select('mdo_id', [], null, ['class' => 'form-control', 'id' => 'mdo_id', 'required', 'value' => Input::get('pes_email'), 'placeholder' => 'Módulo']) }}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('pol_id', 'Polo', ['class' => 'control-label']) !!}
                    {{ Form::select('pol_id', [], null, ['class' => 'form-control', 'id' => 'pol_id', 'value' => Input::get('pol_id'), 'placeholder' => 'Polo']) }}
                </div>
                <div class="form-group col-md-1">
                    <label for="" class="control-label"></label>
                    <button class="btn btn-primary form-control" id="btnLocalizar"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="box box-primary hidden" id="boxAlunos">
        <div class="box-header with-border">
            <h3 class="box-title">Lista de Alunos</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body"></div>
    </div>
@stop

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">
        $(function () {
            $('select').select2();

            selectCursos = $('#crs_id');
            selectOfertas = $('#ofc_id');
            selectTurmas = $('#trm_id');
            selectModulos = $('#mdo_id');
            selectPolos = $('#pol_id');

            // Busca ofertas de curso
            $('#crs_id').change(function () {
                var curso = $(this).val();

                if (curso) {
                    selectOfertas.empty();
                    selectTurmas.empty();
                    selectModulos.empty();

                    $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + curso)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)) {
                                selectOfertas.append('<option value="">Selecione uma oferta</option>');
                                $.each(data, function (key, obj) {
                                    selectOfertas.append("<option value='" + obj.ofc_id + "'>" + obj.ofc_ano + " (" + obj.mdl_nome + ")</option>");
                                });
                            } else {
                                selectOfertas.append('<option value="">Sem ofertas cadastradas</option>');
                            }
                        });
                }
            });

            $('#ofc_id').change(function () {
                var oferta = $(this).val();

                if (oferta) {
                    selectTurmas.empty();
                    selectModulos.empty();

                    $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + oferta)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)) {
                                selectTurmas.append('<option value="">Selecione uma turma</option>');
                                $.each(data, function (key, obj) {
                                    selectTurmas.append("<option value='" + obj.trm_id + "'>" + obj.trm_nome + "</option>");
                                });
                            } else {
                                selectTurmas.append('<option value="">Sem turmas cadastradas</option>');
                            }
                        });


                    $.harpia.httpget("{{url('/')}}/academico/async/cursos/findmodulosbyoferta/" + oferta)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)) {
                                selectModulos.append('<option value="">Selecione um módulo</option>');
                                $.each(data, function (key, obj) {
                                    selectModulos.append("<option value='" + obj.mdo_id + "'>" + obj.mdo_nome + "</option>");
                                });
                            } else {
                                selectModulos.append('<option value="">Sem módulos cadastrados</option>');
                            }
                        });
                }

                if (oferta) {
                    selectPolos.empty();

                    $.harpia.httpget("{{url('/')}}/academico/async/polos/findallbyofertacurso/" + oferta)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)) {
                                selectPolos.append('<option value="0">Selecione um polo</option>');
                                $.each(data, function (key, obj) {
                                    selectPolos.append("<option value='" + obj.pol_id + "'>" + obj.pol_nome + "</option>");
                                });
                            } else {
                                selectPolos.append('<option value="">Sem turmas cadastradas</option>');
                            }
                        });

                }
            });

            $('#btnLocalizar').click(function () {
              var modulo = $('#mdo_id').val();
              var turma = $('#trm_id').val();
              var polo = $('#pol_id').val();

              if(!turma) {
                  swal("Selecione uma turma", "Necessário selecionar uma turma para certificação", "info");
                  return;
              }

              if(!modulo) {
                  swal("Selecione o Módulo", "Necessário selecionar um módulo para certificação", "info");
                  return;
              }

              if (polo == null) {
                polo = '';
              }

              if (turma) {
                  $.harpia.httpget("{{url('/')}}/academico/async/cursos/getalunosaptos/" + turma + "/" + modulo + "/" + polo)
                      .done(function (data) {
                          renderTable(data.aptos, data.certificados, data.aptosq, data.certificadosq);
                      });
              }
            });

            renderTable = function (aptos, certificados, aptosq, certificadosq) {
                var html = '<div class="row"><div class="col-md-12">';
                // criando a estrutura das tabs
                var tabs = '<div class="nav-tabs-custom">';
                tabs += '<ul class="nav nav-tabs">';
                tabs += '<li class="active">' +
                    '<a href="#tab_1" data-toggle="tab">' +
                    'Aptos ' +
                    '<span data-toggle="tooltip" class="badge bg-blue">' + aptosq + '</span>' +
                    '</a></li>';
                tabs += '<li>' +
                    '<a href="#tab_2" data-toggle="tab">' +
                    'Certificados <span data-toggle="tooltip" class="badge bg-blue">' + certificadosq + '</span>' +
                    '</a></li>';
                tabs += '</ul>';
                tabs += '<div class="tab-content">';


                // Criacao da Tab de Alunos nao matriculados para aptos
                var tab1 = '<div class="tab-pane active" id="tab_1">';

                if (!$.isEmptyObject(aptos)) {
                    var div = '<div class="row"><div class="col-md-12">';
                    var table1 = '<table class="table table-bordered table-striped">';

                    // cabeçalho da tabela
                    table1 += '<tr>';
                    table1 += '<th width="1%"><label><input id="select_all" type="checkbox"></label></th>';
                    table1 += '<th>Nome</th>';
                    table1 += '<th width="20%">Situacao </th>';
                    table1 += '</tr>';

                    // corpo da tabela
                    $.each(aptos, function (key, obj) {
                        table1 += '<tr>';
                        if (obj.mof_situacao_matricula == 'no_pre_requisitos') {
                            table1 += '<td></td>';
                        } else {
                            table1 += '<td><label><input class="aptos" type="checkbox" value="' + obj.mat_id + '"></label></td>';
                        }

                        table1 += '<td>' + obj.pes_nome + '</td>';
                        if (obj.mof_situacao_matricula == 'no_pre_requisitos') {
                            table1 += '<td><span class="label label-warning">Pré-requisitos não satisfeitos</span></td>';
                        } else {
                            table1 += '<td><span class="label label-success">Apto para certificação</span></td>';
                        }

                        table1 += '</tr>';
                    });

                    table1 += '</table>';
                    div += table1;
                    div += '</div></div>';

                    // criacao do botao de matricular alunos
                    div += '<div class="row"><div class="col-md-12">';
                    div += '<button class="btn btn-success hidden btnCertificar">Certificar Alunos</button>';
                    div += '</div></div>';

                    tab1 += div;
                } else {
                    tab1 += '<p>Sem alunos para certificar</p>';
                }

                tab1 += '</div>';

                // Criacao da Tab de Alunos certificados
                var tab2 = '<div class="tab-pane" id="tab_2">';

                if (!$.isEmptyObject(certificados)) {
                    var table2 = '<table class="table table-bordered table-striped">';

                    // cabeçalho da tabela
                    table2 += '<tr>';
                    table2 += '<th>Nome</th>';
                    table2 += '<th width="20%">Situacao de Matricula</th>';
                    table2 += '<th width="20%">Impressão de certificado</th>';
                    table2 += '</tr>';

                    // corpo da tabela
                    $.each(certificados, function (key, obj) {

                        table2 += '<tr>';
                        table2 += '<td>' + obj.pes_nome + '</td>';
                        table2 += '<td><span class="label label-info">Certificados</span></td>';
                        table2 += '<td><a target = "_blanck" type="button" href= "{{url('/')}}/academico/async/cursos/printCertificado/'+obj.mat_id+'/'+$('#mdo_id').val()+'" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i></a></td>';
                        table2 += '</tr>';
                    });

                    table2 += '</table>';
                    tab2 += table2;
                } else {
                    tab2 += '<p>Sem alunos certificados</p>';
                }

                tab2 += '</div>';

                tabs += tab1;
                tabs += tab2;

                tabs += '</div></div>';

                html += tabs;
                html += '</div></div>';

                $('#boxAlunos').removeClass('hidden');
                $('#boxAlunos .box-body').empty().append(html);
            }
        });

        // evento para selecionar todos os checkboxes
        $(document).on('click', '#select_all', function (event) {
            if (this.checked) {
                $(':checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function () {
                    this.checked = false;
                });
            }
        });

        var hiddenButton = function () {
            var checkboxes = $('#boxAlunos table td input[type="checkbox"]');

            if (checkboxes.is(':checked')) {
                $(document).find('.btnCertificar').removeClass('hidden');
            } else {
                $(document).find('.btnCertificar').addClass('hidden');
            }
        };

        $(document).on('click', '#boxAlunos table input[type="checkbox"]', hiddenButton);

        $(document).on('click', '.btnCertificar', function () {
            var quant = $('.aptos:checked').length;
            var ofertaId = $('#ofc_id').val();

            if ((!(quant > 0)) || (!ofertaId || ofertaId == '')) {
                return false;
            }

            var aptosIds = new Array();

            $('.aptos:checked').each(function () {
                aptosIds.push($(this).val());
            });


            sendMatriculas(aptosIds, ofertaId);
        });

        var sendMatriculas = function (matriculasIds, ofertaId) {
            var token = "{{csrf_token()}}";
            modulo = selectModulos.val();

            var dados = {
                matriculas: matriculasIds,
                ofd_id: ofertaId,
                modulo: modulo,
                _token: token
            };

            $.harpia.showloading();

            $.ajax({
                type: 'POST',
                url: '/academico/async/cursos/certificaralunos',
                data: dados,
                success: function (data) {
                    $.harpia.hideloading();

                    toastr.success('Alunos certificados com sucesso!', null, {progressBar: true});

                    var turma = selectTurmas.val();

                    $.harpia.httpget("{{url('/')}}/academico/async/cursos/getalunosaptos/" + turma + "/" + modulo)
                        .done(function (data) {

                            renderTable(data.aptos, data.certificados);
                        });
                },
                error: function (xhr, textStatus, error) {
                    $.harpia.hideloading();

                    switch (xhr.status) {
                        case 400:
                            toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                            break;
                        default:
                            toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                    }
                }
            });
        };

    </script>
@stop
