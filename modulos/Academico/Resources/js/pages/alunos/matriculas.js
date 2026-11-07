import $ from 'jquery';
import Swal from 'sweetalert2';
import * as bootstrap from 'bootstrap';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

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
                var observacao = $('#observacao_situacao' + modal).val();

                var data = {
                    id: matricula,
                    situacao: situacao,
                    observacao: observacao,
                    _token: csrfToken
                };

                // Volta a usar o POST assíncrono agora que o PHP vai parar de dar erro 500
                // $.harpia.httppost(baseUrl + '/academico/async/matricula/alterarsituacao', data).done(function(response) {
                //     if (response !== false) {
                //         $('#matricula-modal' + modal).modal('hide');
                //         Swal.fire({
                //             title: "Sucesso!",
                //             text: "Situação da matrícula atualizada.",
                //             icon: "success",
                //             timer: 1500,
                //             showConfirmButton: false
                //         }).then(() => {
                //             location.reload(true);
                //         });
                //     }
                // });
                $.ajax({
                    url: baseUrl + '/academico/async/matricula/alterarsituacao',
                    type: 'POST',
                    data: data,

                    success: function(resp) {

                        $('#matricula-modal' + modal).modal('hide');

                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Situação atualizada.',
                            icon: 'success'
                        }).then(() => location.reload());
                    },

                    error: function(xhr) {

                        let msg = 'Algo estranho aconteceu.';

                        if (xhr.responseText) {
                            msg = xhr.responseText;
                        }

                        Swal.fire({
                            title: 'Erro',
                            text: msg,
                            icon: 'error'
                        });
                    }
                });
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

        $.harpia.httpget(baseUrl + "/academico/async/polos/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
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
        $.harpia.httpget(baseUrl + "/academico/async/grupos/findallbyturmapolo/" + turmaId + "/" + poloId).done(function (response) {
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
            var observacao = $('#observacao_pologrupo' + window.modalId).val();

            var data = {
                _method: "PUT",
                mat_pol_id: polo,
                mat_grp_id: grupo,
                observacao: observacao,
                _token: csrfToken
            };

            $.harpia.showloading();

            // Retornando para $.ajax para garantir que o modal feche suavemente
            $.ajax({
                url: baseUrl + '/academico/matricularalunocurso/edit/' + window.matricula,
                type: "POST",
                data: data,
                success: function (resp) {
                    $.harpia.hideloading();
                    $('.modalUpdatePolo' + window.modalId).modal('hide');

                    Swal.fire({
                        title: "Sucesso!",
                        text: "Polo e grupo atualizados com sucesso.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(true);
                    });
                },
                error: function (e) {
                    $.harpia.hideloading();
                    Swal.fire("Oops...", "Algo estranho aconteceu! Se o problema persistir, entre em contato com a administração do sistema.", "error");
                }
            });
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