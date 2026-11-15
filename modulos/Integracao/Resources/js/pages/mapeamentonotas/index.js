import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function () {
    var token = csrfToken;

    var cursosSelect = $('#crs_id');
    var ofertasCursoSelect = $('#ofc_id');
    var turmaSelect = $('#trm_id');

    // carrega as ofertas de curso do curso escolhido
    function carregarOfertasCurso(cursoId) {
        // limpando selects
        ofertasCursoSelect.empty();
        turmaSelect.empty();

        if(!cursoId) {
            return $.Deferred().resolve().promise();
        }

        // faz a consulta pra trazer todas as ofertas de curso
        return $.harpia.httpget(baseUrl + '/academico/async/ofertascursos/findallbycurso/' + cursoId).done(function (response) {
            if(!$.isEmptyObject(response)) {
                ofertasCursoSelect.append("<option value=''>Selecione uma oferta</option>");

                $.each(response, function (key, obj) {
                    ofertasCursoSelect.append("<option value='"+obj.ofc_id+"'>"+obj.ofc_ano+" ("+obj.mdl_nome+")</option>");
                });
            } else {
                ofertasCursoSelect.append("<option value=''>Sem ofertas cadastradas</option>");
            }
        });
    }

    // carrega as turmas da oferta de curso escolhida
    function carregarTurmas(ofertaCursoId) {
        // limpando selects
        turmaSelect.empty();

        if(!ofertaCursoId) {
            return $.Deferred().resolve().promise();
        }

        // buscar turmas
        return $.harpia.httpget(baseUrl + "/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
            if(!$.isEmptyObject(response)) {
                turmaSelect.append("<option value=''>Selecione uma turma</option>");

                $.each(response, function (key, obj) {
                    turmaSelect.append("<option value='"+obj.trm_id+"'>"+obj.trm_nome+"</option>");
                });
            } else {
                turmaSelect.append("<option value=''>Sem turmas cadastradas</option>");
            }
        });
    }

    // busca as disciplinas da turma selecionada
    function buscarDisciplinas() {
        var curso = $('#crs_id').val();
        var ofertaCurso = $('#ofc_id').val();
        var turma = $('#trm_id').val();

        if (!curso || !ofertaCurso || !turma) {
            return false;
        }

        var dados = {
            _token: token,
            crs_id: curso,
            ofc_id: ofertaCurso,
            trm_id: turma
        };

        var mapeamentonotas = window.PageRoutes.mapeamentonotas

        $.ajax({
            type: 'POST',
            url: mapeamentonotas,
            data: dados,
            success: function (response) {
                $('#disciplinas').empty();
                $('#disciplinas').append(response.html);
            }
        });
    }

    // evento change do select de cursos
    cursosSelect.change(function () {
        carregarOfertasCurso($(this).val());
    });

    // evento change do select de ofertas de curso
    ofertasCursoSelect.change(function () {
        carregarTurmas($(this).val());
    });

    $('#btnBuscar').click(function (e) {
        e.preventDefault();

        buscarDisciplinas();
    });

    // ao voltar da tela de alunos, restaura o filtro e já traz as disciplinas
    var params = new URLSearchParams(window.location.search);

    if (params.get('crs_id') && params.get('ofc_id') && params.get('trm_id')) {
        cursosSelect.val(params.get('crs_id')).trigger('change.select2');

        carregarOfertasCurso(params.get('crs_id')).then(function () {
            ofertasCursoSelect.val(params.get('ofc_id')).trigger('change.select2');

            return carregarTurmas(params.get('ofc_id'));
        }).then(function () {
            turmaSelect.val(params.get('trm_id')).trigger('change.select2');

            buscarDisciplinas();
        });
    }

    $('#disciplinas').on('click', '.btnSalvar', function(event) {
        var ofd_id = $(event.currentTarget).data('id');

        var nota1 = $('#'+ofd_id+'_nota1').val();
        var nota2 = $('#'+ofd_id+'_nota2').val();
        var nota3 = $('#'+ofd_id+'_nota3').val();
        var conceito = $('#'+ofd_id+'_conceito').val();
        var recuperacao = $('#'+ofd_id+'_recuperacao').val();
        var final = $('#'+ofd_id+'_final').val();
        var aproveitamento = $('#'+ofd_id+'_aproveitamento').val();

        if (nota1 != '' || nota2 != '' || nota3 != '' || conceito != '' || recuperacao != '' || final != '') {

            var dados = {
                _token: token,
                data: JSON.stringify({
                    'min_ofd_id': ofd_id,
                    'min_id_nota1': nota1,
                    'min_id_nota2': nota2,
                    'min_id_nota3': nota3,
                    'min_id_conceito': conceito,
                    'min_id_recuperacao': recuperacao,
                    'min_id_final': final,
                    'min_id_aproveitamento': aproveitamento
                })
            };

            $.harpia.showloading();

            $.ajax({
                type: 'POST',
                url: "/integracao/async/mapeamentonotas/setmapeamentonotas",
                data: dados,
                success: function (response) {
                    $.harpia.hideloading();

                    var msg = response.msg;

                    toastr.success(msg, null, {progressBar: true});

                    $(event.currentTarget).prop('disabled', true);
                },
                error: function (response) {
                    $.harpia.hideloading();

                    var msg = response.responseJSON.error;

                    toastr.error(msg, null, {progressBar: true});
                }
            });
        }
    });

});