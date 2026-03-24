@extends('layouts.modulos.default')

@section('title')
    Diplomas
@stop

@section('subtitle')
    Gerenciamento de impressão de diplomas
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-3 px-1">
                        <label for="crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
                        <select name="crs_id" id="crs_id" class="form-control">
                            <option value="">Escolha um curso</option>
                            @foreach($cursos as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3 px-1">
                        <label for="ofc_id" class="form-label">Oferta <small class="obrigatorio-dot">*</small></label>
                        <select name="ofc_id" class="form-control" id="ofc_id" value="Request"></select>
                    </div>
                    <div class="form-group col-md-3 px-1">
                        <label for="trm_id" class="form-label">Turma <small class="obrigatorio-dot">*</small></label>
                        <select name="trm_id" class="form-control" id="trm_id" value="Request"></select>
                    </div>
                    <div class="form-group col-md-2 px-1">
                        <label for="pol_id" class="form-label">Polo <small class="obrigatorio-dot">*</small></label>
                        <select name="pol_id" class="form-control" id="pol_id" value="Request"></select>
                    </div>
                    <div class="form-group col-md-1 px-1">
                        <label for="" class="form-label"></label>
                        <button class="btn btn-primary w-100" id="btnLocalizar"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="row py-2">
        <div class="card card-primary card-outline hidden" id="cardAlunos">
            <div class="card-header with-border">
                <h3 class="card-title">Lista de Alunos</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body"></div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(function () {
            selectCursos = $('#crs_id');
            selectOfertas = $('#ofc_id');
            selectTurmas = $('#trm_id');
            selectPolos = $('#pol_id');


            // Busca ofertas de curso
            $('#crs_id').change(function () {
                var curso = $(this).val();

                if (curso) {
                    selectOfertas.empty();
                    selectTurmas.empty();

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
              var turma = $('#trm_id').val();
              var polo = $('#pol_id').val();

              if (polo == null) {
                polo = 0;
              }

              if (turma) {
                  $.harpia.httpget("{{url('/')}}/academico/async/diplomas/getalunosdiplomados/" + turma + "/" + polo)
                      .done(function (data) {

                          renderTable(data.aptos, data.diplomados);
                      });
              }
            });

            renderTable = function (aptos, diplomados) {
                var html = '<div class="row"><div class="col-md-12">';
                // criando a estrutura das tabs
                var tabs = '<div class="card card-primary card-outline card-outline-tabs">' +
                    '<div class="card-header p-0 border-bottom-0">';
                tabs += '<ul class="nav nav-tabs" id="tabs-certificacao" role="tablist">';
                tabs += '<li class="nav-item">' +
                    '<a class="nav-link active" id="tab-aptos" data-bs-toggle="tab" href="#tab_1" role="tab" aria-controls="tab_1" aria-selected="true">' +
                    'Aptos' +
                    '</a>' +
                    '</li>';
                tabs += '<li class="nav-item">' +
                    '<a class="nav-link" id="tab-certificados" data-bs-toggle="tab" href="#tab_2" role="tab" aria-controls="tab_2" aria-selected="false">' +
                    'Certificados' +
                    '</a>' +
                    '</li>';
                tabs += '</ul>';
                tabs += '</div>' +
                    '<div class="card-body">' +
                    '<div class="tab-content" id="tabs-certificacao-content">';


                // Criacao da Tab de Alunos nao matriculados para aptos
                var tab1 = '<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab-aptos">';

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
                            table1 += '<td><span class="label label-success">Apto para diplomação</span></td>';
                        }

                        table1 += '</tr>';
                    });

                    table1 += '</table>';
                    div += table1;
                    div += '</div></div>';

                    // criacao do botao de diplomar alunos
                    div += '<div class="row"><div class="col-md-12">';
                    div += '<button class="btn btn-success hidden btnDiplomar">Diplomar Alunos</button>';
                    div += '</div></div>';

                    tab1 += div;
                } else {
                    tab1 += '<p>Sem alunos para diplomar</p>';
                }

                tab1 += '</div>';

                // Criacao da Tab de Alunos diplomados
                var tab2 = '<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab-certificados">';

                if (!$.isEmptyObject(diplomados)) {
                  var table2 = '';
                                    table2 = '<div class="row">';
                                    table2 += '<form action="{{route('academico.diplomas.imprimirdiplomas')}}" method="POST">';
                                    table2 += '<div class="col-md-12">';
                                    table2 += '{{csrf_field()}}';
                                    table2 += '<table class="table table-bordered table-hover">';

                                    table2 += '<tr>';
                                    table2 += '<th width="1%"><label><input id="select_all" type="checkbox"></label></th>';
                                    table2 += '<th width="1%">#</th>';
                                    table2 += '<th>Aluno</th>';
                                    table2 += '</tr>';

                                    $.each(diplomados, function (key, obj) {
                                        table2 += '<tr>';
                                        table2 += '<td><label><input type="checkbox" name="diplomas[]" class="diplomas" value="'+obj.dip_id+'"></label></td>';
                                        table2 += '<td>'+obj.dip_id+'</td>';
                                        table2 += '<td>'+obj.pes_nome+'</td>';
                                        table2 += '</tr>';
                                    });

                                    table2 += '</table>';

                                    table2 += "<div class='form-group'>";
                                    table2 += "<button type='submit' class='btn btn-primary btnImprimirDiplomas hidden' formtarget='_blank'><i class='fa fa-file-pdf-o'></i> Imprimir Diplomas</button>";
                                    table2 += "</div>";
                                    table2 += "</div>";
                                    table2 += "</form>";
                                    table2 += "</div>";
                    tab2 += table2;
                } else {
                    tab2 += '<p>Sem alunos diplomados</p>';
                }

                tab2 += '</div></div></div></div>';

                tabs += tab1;
                tabs += tab2;

                tabs += '</div></div>';

                html += tabs;
                html += '</div></div>';

                $('#cardAlunos').removeClass('hidden');
                $('#cardAlunos .card-body').empty().append(html);
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
            var checkboxes = $('#cardAlunos table td input[type="checkbox"]');

            if (checkboxes.is(':checked')) {
                $(document).find('.btnImprimirDiplomas').removeClass('hidden');
            } else {
                $(document).find('.btnImprimirDiplomas').addClass('hidden');
            }
        };

        var hiddenButtonDiplomar = function () {
            var checkboxes = $('#cardAlunos table td input[type="checkbox"]');

            if (checkboxes.is(':checked')) {
                $(document).find('.btnDiplomar').removeClass('hidden');
            } else {
                $(document).find('.btnDiplomar').addClass('hidden');
            }
        };

        $(document).on('click', '.btnDiplomar', function () {
            var quant = $('.aptos:checked').length;

            if ((!(quant > 0))) {
                return false;
            }

            var aptosIds = new Array();

            $('.aptos:checked').each(function () {
                aptosIds.push($(this).val());
            });

            sendMatriculas(aptosIds);
        });

        var sendMatriculas = function (matriculasIds) {
            var token = "{{csrf_token()}}";

            var dados = {
                matriculas: matriculasIds,
                _token: token
            };

            $.harpia.showloading();

            $.ajax({
                type: 'POST',
                url: '/academico/async/diplomas/diplomaralunos',
                data: dados,
                success: function (data) {
                    $.harpia.hideloading();

                    toastr.success('Alunos diplomados com sucesso!', null, {progressBar: true});

                    var turma = selectTurmas.val();
                    var polo = selectPolos.val();

                    $.harpia.httpget("{{url('/')}}/academico/async/diplomas/getalunosdiplomados/" + turma + "/" + polo)
                        .done(function (data) {

                            renderTable(data.aptos, data.diplomados);
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

        $(document).on('click', '#cardAlunos table input[type="checkbox"]', hiddenButton);
        $(document).on('click', '#cardAlunos table input[type="checkbox"]', hiddenButtonDiplomar);

    </script>
@stop
