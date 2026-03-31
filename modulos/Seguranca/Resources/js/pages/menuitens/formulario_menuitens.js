import $ from 'jquery';

$(function() {
    const baseUrl = $('meta[name="base-url"]').attr('content');

    $('#mit_mod_id').change(function() {

        var modulo = $(this).val();

        $('#mit_item_pai').empty();

        $.harpia.httpget(baseUrl + "/seguranca/async/menuitens/getitenbymodulo/"+modulo).done(function(data) {
            $('#mit_item_pai').append('<option value="">Selecione um item</option>');
            if (!$.isEmptyObject(data)) {
                $.each(data, function(key, value) {
                    $('#mit_item_pai').append('<option value="'+key+'">'+value+'</option>');
                });
            } else {
                $('#mit_item_pai').append('<option value="">Não há itens cadastrados</option>');
            }
        });
    });
});