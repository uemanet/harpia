import $ from 'jquery';

$(function() {
    const baseUrl = $('meta[name="base-url"]').attr('content');

    $('#mod_id').change(function (e) {
        var moduloId = $(this).val();

        if(!moduloId)
        {
            return false;
        }

        $.harpia.httpget(baseUrl + '/seguranca/async/perfis/findallbymodulo/' + moduloId).done(function (data) {
            $('#prf_id').empty();
            if($.isEmptyObject(data)) {
                $('#prf_id').append("<option value='' selected>Sem perfis associados</option>");
            } else {
                $('#prf_id').append("<option value='' selected>Selecione um perfil</option>");
                $.each(data, function (key, value) {

                    $('#prf_id').append("<option value=" + value.prf_id + " >" + value.prf_nome + "</option>");
                });

            }
        });
    });

    $('#btnAtribuir').click(function (e) {
        e.preventDefault();

        var modulo = $('#mod_id').val();
        var perfil = $('#prf_id').val();

        if(modulo == '' || perfil == '') {
            return false;
        }

        $('#formAtribuirPerfil').submit();
    })
});