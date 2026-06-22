import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function () {
    var selectCursos = $('#crs_id');
    var selectOfertas = $('#ofc_id');
    var selectTurmas = $('#trm_id');
    var selectModulos = $('#mdo_id');
    var selectPolos = $('#pol_id');

    // Busca ofertas de curso
    $('#crs_id').change(function () {
        var curso = $(this).val();

        if (curso) {
            selectOfertas.empty();
            selectTurmas.empty();
            selectModulos.empty();

            $.harpia.httpget(baseUrl + "/academico/async/ofertascursos/findallbycurso/" + curso)
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

            $.harpia.httpget(baseUrl + "/academico/async/turmas/findallbyofertacurso/" + oferta)
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


            $.harpia.httpget(baseUrl + "/academico/async/cursos/findmodulosbyoferta/" + oferta)
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

            $.harpia.httpget(baseUrl + "/academico/async/polos/findallbyofertacurso/" + oferta)
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
    // certificação
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
            $.harpia.httpget(baseUrl + "/academico/async/cursos/getalunosaptos/" + turma + "/" + modulo + "/" + polo)
                .done(function (data) {
                    renderTable(data.aptos, data.certificados, data.aptosq, data.certificadosq);
                });
        }
    });

    var renderTable = function (aptos, certificados, aptosq, certificadosq) {
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
        var tab2 = '<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab-certificados">';

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
                table2 += '<td><span class="label label-info text-center">Certificado</span></td>';
                table2 += '<td><a target = "_blanck" type="button" href= baseUrl + "/academico/async/cursos/printCertificado/'+obj.mat_id+'/'+$('#mdo_id').val()+'" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i></a></td>';
                table2 += '</tr>';
            });

            table2 += '</table>';
            tab2 += table2;
        } else {
            tab2 += '<p>Sem alunos certificados</p>';
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
        $(document).find('.btnCertificar').removeClass('hidden');
    } else {
        $(document).find('.btnCertificar').addClass('hidden');
    }
};

$(document).on('click', '#cardAlunos table input[type="checkbox"]', hiddenButton);

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
    var token = csrfToken;
    var modulo = selectModulos.val();

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

            $.harpia.httpget(baseUrl + "/academico/async/cursos/getalunosaptos/" + turma + "/" + modulo)
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