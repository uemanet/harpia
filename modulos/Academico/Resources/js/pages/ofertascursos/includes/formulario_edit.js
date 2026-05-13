import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function () {
    $('#ofc_crs_id').prop('selectedIndex', 0);
});

$('#ofc_crs_id').change(function () {
    var cursoId = $("#ofc_crs_id").val();

    if (!cursoId) {
        return;
    }

    $.harpia.httpget(baseUrl + '/academico/async/matrizescurriculares/findallbycurso/' + cursoId).done(function (result) {

        $("#ofc_mtc_id").empty();

        if ($.isEmptyObject(result)) {
            $('#ofc_mtc_id').append('<option value=#>Sem matrizes curriculares cadastradas</option>');
        } else {
            $("#ofc_mtc_id").append("<option value='' selected>Selecione uma matriz curricular</option>");
            $.each(result, function (key, value) {
                $('#ofc_mtc_id').append('<option value=' + value.mtc_id + ' >' + value.mtc_titulo + '</option>');
            });
        }

        $('#ofc_mtc_id').focus();
    });
});