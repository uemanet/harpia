import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function () {

    var lancamentotccId = "{{$lancamentoTcc->ltc_id}}"
    var csrf_token = "{{csrf_token()}}";

    $(document).on('click', '.btn-delete', function (event) {
        event.preventDefault();

        swal({
            title: "Tem certeza que deseja excluir?",
            text: "Você não poderá recuperar essa informação!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, pode excluir!",
            cancelButtonText: "Não, quero cancelar!",
            closeOnConfirm: true
        }, function(isConfirm){
            if (isConfirm) {

                var data = {ltc_id: lancamentotccId, _token : csrf_token};

                $.harpia.showloading();

                var result = false;

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/anexos/deletaranexolancamentotcc',
                    data: data,
                    success: function (data) {
                        $.harpia.hideloading();

                        toastr.success('Anexo excluído com sucesso!', null, {progressBar: true});
                        $(".first").attr("placeholder", "Sem anexo").val("").focus().blur();
                        $(".botaoDelete").remove();
                    },
                    error: function (xhr, textStatus, error) {
                        $.harpia.hideloading();

                        switch (xhr.status) {
                            case 400:
                                toastr.error('Sem anexos para serem excluídos!', null, {progressBar: true});
                                break;
                            default:
                                toastr.error(xhr.responseText, null, {progressBar: true});

                                result = false;
                        }
                    }
                });
            }
        });

    });

});