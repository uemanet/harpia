import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function () {
    $('#btnBuscar').click(function (e) {

        var lista = $('#lst_id').val();
        var turma = $('#trm_id').val();

        if (!lista || !turma) {
            return false;
        }

        renderTable(lista, turma);
    });

    $('#tabela').on('click', '.btnDelete', function (e) {
        e.preventDefault();

        var button = $(this);

        // Corrigido para a sintaxe moderna do SweetAlert2
        Swal.fire({
            title: "Tem certeza que deseja excluir?",
            text: "Você não poderá recuperar essa informação!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Sim, pode excluir!",
            cancelButtonText: "Não, quero cancelar!"
        }).then((result) => {
            if (result.isConfirmed) {

                var lista = $('#lst_id').val();
                var matricula = button.data('mat-id');
                var turma = $('#trm_id').val();

                var data = {
                    lst_id: lista,
                    mat_id: matricula,
                    _token: csrfToken
                };

                $.harpia.showloading();

                $.ajax({
                    type: 'POST',
                    url: '/academico/carteirasestudantis/deletematricula',
                    data: data,
                    success: function (response) {
                        $.harpia.hideloading();

                        toastr.success(response, null, {progressBar: true});
                        renderTable(lista, turma);
                    },
                    error: function (xhr) {
                        $.harpia.hideloading();

                        // Blindagem do erro de tipagem no Ajax
                        var errorMessage = "Erro ao processar a requisição.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            errorMessage = xhr.responseText.replace(/\"/g, '');
                        }

                        toastr.error(errorMessage, null, {progressBar: true});
                    }
                });
            }
        });

    });

    function renderTable(listaId, turmaId) {
        $('#tabela').empty();

        $.ajax({
            method: 'GET',
            url: '/academico/async/carteirasestudantis/gettableshowmatriculas/'+listaId+'/'+turmaId,
            success: function (res) {
                $('#tabela').append(res);
            },
            error: function (xhr) {
                // Blindagem do erro de tipagem no Ajax
                var errorMessage = "Erro ao carregar a tabela.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMessage = xhr.responseText.replace(/\"/g, '');
                }

                toastr.error(errorMessage, null, {progressBar: true});
            }
        });
    };
});