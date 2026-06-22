import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function(){
    $('#crs_id').prop('selectedIndex',0);
});

$('#crs_id').change(function (e) {
    var crsId = $(this).val();

    var selectOfertas = $('#ofc_id');
    var selectTurmas = $('#atr_trm_id');
    if(crsId) {

        // Populando o select de ofertas de cursos
        selectOfertas.empty();
        selectTurmas.empty();

        $.harpia.httpget(baseUrl + "/academico/async/ofertascursos/findallbycursowithoutpresencial/" + crsId)
            .done(function (data) {
                if(!$.isEmptyObject(data)) {
                    selectOfertas.append("<option>Selecione a oferta</option>");
                    $.each(data, function (key, value) {
                        selectOfertas.append('<option value="'+value.ofc_id+'">'+value.ofc_ano+' ('+value.mdl_nome+')</option>');
                    });
                } else {
                    selectOfertas.append("<option>Sem ofertas cadastradas</option>");

                }
            });
    }
});


$('#ofc_id').change(function (e) {
    var ofertaId = $(this).val();

    var selectTurmas = $('#atr_trm_id');

    if (ofertaId) {
        selectTurmas.empty();

        $.harpia.httpget(baseUrl + '/academico/async/turmas/findallbyofertacursowithoutambiente/' + ofertaId)
            .done(function (data) {
                if (!$.isEmptyObject(data)){
                    selectTurmas.append('<option>Selecione a turma</option>');
                    $.each(data, function (key, obj) {
                        selectTurmas.append('<option value="'+obj.trm_id+'">'+obj.trm_nome+'</option>')
                    });
                }else {
                    selectTurmas.append('<option>Sem turmas cadastradas</option>')
                }
            });
    }

})

$(document).on('click', '.btn-success', function (event) {
    event.preventDefault();

    var button = $(this);

    swal({
        title: "Tem certeza que deseja alterar a versão dessa turma?",
        text: "Essa alteração é irreversível!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim!",
        cancelButtonText: "Não!",
        closeOnConfirm: true
    }, function(isConfirm){
        if (isConfirm) {
            button.closest("form").submit();
        }
    });
});
