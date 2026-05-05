import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function () {
    var selectOfertas = $('#ofc_id');
    var selectTurmas = $('#trm_id');
    var selectPeriodos = $("#per_id");

    var cardDisciplinas = $('#cardDisciplinas');

    // populando o select de ofertas de curso
    $('#crs_id').change(function () {
        var curso = $(this).val();

        if (curso) {
            selectOfertas.empty();
            selectTurmas.empty();
            selectPeriodos.empty();

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

    // populando o select de turmas
    selectOfertas.change(function () {
        var oferta = $(this).val();

        if (oferta) {
            selectTurmas.empty();
            selectPeriodos.empty();

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
    });

    selectTurmas.change(function () {
        var turmaId = $(this).val();

        if (turmaId) {
            // limpando selects
            selectPeriodos.empty();
            $.harpia.httpget("{{url('/')}}/academico/async/periodosletivos/findallbyturma/" + turmaId)
                .done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        selectPeriodos.append("<option value=''>Selecione um periodo</option>");
                        $.each(response, function (key, obj) {
                            selectPeriodos.append("<option value='" + obj.per_id + "'>" + obj.per_nome + "</option>");
                        });
                    } else {
                        selectPeriodos.append("<option value=''>Sem períodos disponíveis</option>");
                    }
                });
        }
    });

    // evento click no botão de pesquisar ofertas
    $('#btnLocalizar').click(function () {
        var turma = selectTurmas.val();
        var periodo = selectPeriodos.val();

        if (turma == '' || periodo == '') {
            return false;
        }

        var url = "{{url('/')}}/academico/async/ofertasdisciplinas/gettableofertasdisciplinas?"+
        "ofd_trm_id=" + turma + "&ofd_per_id=" + periodo;

        $.harpia.showloading();
        $.ajax({
            method: 'GET',
            url: url,
            success: function(response) {
                $.harpia.hideloading();
                $('#table-ofertas').empty();
                $('#table-ofertas').append(response.html)
            },
            error: function(response) {
                $.harpia.hideloading();
                toastr.error('Erro ao processar requisição. Entrar em contato com o suporte.', null, {progressBar: true});
            }
        });

    });
});