import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function() {

    var token = csrfToken;

    var selectCursos = $('#crs_id');
    var selectOfertasCursos = $('#ofc_id');
    var selectTurmas = $('#ofd_trm_id');
    var selectMatrizCurricular = $('#mtc_id');
    var selectModulosMatriz = $('#ofd_mdo_id');
    var selectPeriodosLetivos = $('#ofd_per_id');

    // populando select de matrizes e ofertas de cursos
    selectCursos.change(function (e) {
        var crsId = $(this).val();

        if(crsId) {

            // limpando todos os selects
            selectOfertasCursos.empty();
            selectMatrizCurricular.empty();
            selectTurmas.empty();
            selectModulosMatriz.empty();
            selectPeriodosLetivos.empty();

            // Populando o select de ofertas de cursos
            $.harpia.httpget(baseUrl + "/academico/async/ofertascursos/findallbycurso/" + crsId)
            .done(function (data) {
                if(!$.isEmptyObject(data)) {
                    selectOfertasCursos.append("<option value=''>Selecione a oferta</option>");
                    $.each(data, function (key, value) {
                        selectOfertasCursos.append('<option value="'+value.ofc_id+'">'+value.ofc_ano+' ('+value.mdl_nome+')</option>');
                    });
                } else {
                    selectOfertasCursos.append("<option value=''>Sem ofertas cadastradas</option>");
                }
            });
        }
    });

    // populando select de turmas
    selectOfertasCursos.change(function (e) {
        var ofertaId = $(this).val();

        if (ofertaId) {
            // limpando selects
            selectMatrizCurricular.empty();
            selectTurmas.empty();
            selectModulosMatriz.empty();
            selectPeriodosLetivos.empty();

            // populando o select de matriz curricular
            $.harpia.httpget(baseUrl + '/academico/async/matrizescurriculares/findbyofertacurso/' + ofertaId)
            .done(function (response) {
                var mtc_id = '';
                if (!$.isEmptyObject(response)) {
                    mtc_id = response.mtc_id;
                    selectMatrizCurricular.append('<option value="'+response.mtc_id+'">'+response.mtc_titulo+'</option>')
                } else {
                    selectMatrizCurricular.append('<option value="">Sem matriz cadastrada</option>')
                }

                // populando o select de modulos da matriz curricular
                $.harpia.httpget(baseUrl + '/academico/async/modulosmatriz/findallbymatriz/' + mtc_id)
                .done(function (data) {
                    if(!$.isEmptyObject(data)) {
                        selectModulosMatriz.append('<option value="">Selecione o módulo</option>');
                        $.each(data, function (key, obj) {
                            var option = '<option value="'+obj.mdo_id+'"';
                            if (key == 0) {
                                option += ' selected';
                            }
                            option += '>'+obj.mdo_nome+'</option>';
                            selectModulosMatriz.append(option);
                        });
                    } else {
                        selectModulosMatriz.append('<option value="">Sem módulos cadastrados</option>');
                    }
                });
            });

            // populando o select de turmas
            $.harpia.httpget(baseUrl + '/academico/async/turmas/findallbyofertacurso/' + ofertaId)
            .done(function (data) {
                if (!$.isEmptyObject(data)){
                    selectTurmas.append('<option value="">Selecione a turma</option>');
                    $.each(data, function (key, obj) {
                        selectTurmas.append('<option value="'+obj.trm_id+'">'+obj.trm_nome+'</option>')
                    });
                }else {
                    selectTurmas.append('<option value="">Sem turmas cadastradas</option>')
                }
            });
        }

    });

    // populando select de periodos letivos
    selectTurmas.change(function() {
        var turmaId = $(this).val();

        if(turmaId) {
            // limpando selects
            selectPeriodosLetivos.empty();
            $.harpia.httpget(baseUrl + "/academico/async/periodosletivos/findallbyturma/"+turmaId)
            .done(function(response) {
                if(!$.isEmptyObject(response))
                {
                    selectPeriodosLetivos.append("<option value=''>Selecione um periodo</option>");
                    $.each(response, function (key, obj) {
                        selectPeriodosLetivos.append("<option value='"+obj.per_id+"'>"+obj.per_nome+"</option>");
                    });
                } else {
                    selectPeriodosLetivos.append("<option value=''>Sem períodos disponíveis</option>");
                }
            });
        }
    });

    // Botao de Localizar Disciplinas Ofertadas
    $('#btnLocalizar').click(function () {
        var turma = selectTurmas.val();
        var periodo = selectPeriodosLetivos.val();
        var modulo = selectModulosMatriz.val();

        console.log('teste')

        if(turma == '' || periodo == '' || modulo == '') {
            return false;
        }

        renderTables(turma, periodo, modulo);
    });

    $(document).on('click', '.btnAdicionar', function (e) {
        e.preventDefault();

        var disciplina = $(e.target).data('mdc');
        var tipo_avaliacao = $(e.target).closest('tr').find('.tipo-avaliacao').val();
        var qtd_vagas = $(e.target).closest('tr').find('.qtd-vagas').val();
        var professor = $(e.target).closest('tr').find('.professor').val();
        var turma = selectTurmas.val();
        var periodo = selectPeriodosLetivos.val();
        var modulo = selectModulosMatriz.val();

        if (turma == '' || periodo == '' || professor == '' || !(qtd_vagas > 0)
            || tipo_avaliacao == '' || disciplina == '') {
            return false;
        }

        var dados = {
            ofd_trm_id: turma,
            ofd_per_id: periodo,
            ofd_prf_id: professor,
            ofd_mdc_id: disciplina,
            ofd_qtd_vagas: qtd_vagas,
            ofd_tipo_avaliacao: tipo_avaliacao,
            _token: token
        };

        $.harpia.showloading();

        $.ajax({
            url: baseUrl + "/academico/async/ofertasdisciplinas/oferecerdisciplina",
            data: dados,
            method: 'POST',
            success: function(response) {
                $.harpia.hideloading();
                toastr.success(response.message, null, {progressBar: true});
                renderTables(turma, periodo, modulo);
            },
            error: function(response) {
                $.harpia.hideloading();
                var obj = response.responseJSON;
                toastr.error(obj.error, null, {progressBar: true});
            }
        });
    });

    var renderTableOfertasDisciplinas = function (turmaId, periodoId) {
        var url = baseUrl + "/academico/async/ofertasdisciplinas/gettableofertasdisciplinas?" +
                "ofd_trm_id=" + turmaId + "&ofd_per_id=" + periodoId;

        $.harpia.showloading();
        $.ajax({
            method: 'GET',
            url: url,
            success: function(response) {
                $.harpia.hideloading();
                $('#table-ofertas').empty();
                $('#table-ofertas').append(response.html);
                $('select').select2();
            },
            error: function(response) {
                $.harpia.hideloading();
                toastr.error('Erro ao processar requisição. Entrar em contato com o suporte.', null, {progressBar: true});
            }
        });
    };

    var renderTableDisciplinasNaoOfertadas = function(turmaId, periodoId, moduloId) {
        var url = baseUrl + "/academico/async/ofertasdisciplinas/gettabledisciplinasnaoofertadas?" +
            "ofd_trm_id=" + turmaId + "&ofd_per_id=" + periodoId + "&mdo_id=" + moduloId;

        $.harpia.showloading();
        $.ajax({
            method: 'GET',
            url: url,
            success: function(response) {
                $.harpia.hideloading();
                $('#table-disciplinas').empty();
                $('#table-disciplinas').append(response.html);
                $('#table-disciplinas select').select2();
            },
            error: function(response) {
                $.harpia.hideloading();
                toastr.error('Erro ao processar requisição. Entrar em contato com o suporte.', null, {progressBar: true});
            }
        });
    };

    var renderTables = function(turma, periodo, modulo) {
        renderTableOfertasDisciplinas(turma, periodo);
        renderTableDisciplinasNaoOfertadas(turma, periodo, modulo);
    };

});