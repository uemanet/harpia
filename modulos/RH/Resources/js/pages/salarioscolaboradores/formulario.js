import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function(){
    $('#fpg_id').prop('selectedIndex',0);

    // $("#unidade").hide();

    $('#fpg_id').change(function() {

        var fpgId = $("#fpg_id").val();

        if (!fpgId) {
            return;
        }

        $.harpia.httpget(baseUrl + '/rh/async/fontespagadoras/' + fpgId +'/vinculosfontespagadoras').done(function(result){

            $("#scb_vfp_id").empty();

            if ($.isEmptyObject(result)) {
                $('#scb_vfp_id').append('<option value=#>Sem formas de pagamentos cadastradas</option>');
            } else {
                $("#scb_vfp_id").append("<option value='' selected>Selecione uma forma de pagamento</option>");
                $.each(result, function(key, value) {

                    if(value.vin_descricao == "Bolsa"){

                        if(value.vfp_unidade == 1){
                            var unitario = 'Sim';
                        } else{
                            var unitario = 'Não';
                        }

                        $('#scb_vfp_id').append('<option value=' + value.vfp_id + ' data-valor=' + value.vfp_valor + ' data-uni=' + unitario + '>' + value.vin_descricao + " | Salário Base (R$"+ value.vfp_valor + ") Uni:"+ unitario +'</option>');
                    } else{
                        var unitario = 'Não';

                        $('#scb_vfp_id').append('<option value=' + value.vfp_id + ' data-uni=' + unitario + ' >' + value.vin_descricao + '</option>');
                    }
                });
            }

            $('#scb_vfp_id').focus();
        });
    });


    $('#scb_vfp_id').change(function (e){
        e.preventDefault();

        $("#scb_qtd_pagamento").val(0);
        $("#scb_valor").val(0);

        var unidade = $(e.currentTarget).find(":selected").data('uni');
        var valor = parseFloat($(e.currentTarget).find(":selected").data('valor'));
        if(unidade == 'Não'){
            $("#unidade").hide();
            $('#scb_valor').focus();
            $('#scb_valor').removeAttr('readonly');
        }else{

            $("#unidade").show();
            $('#scb_valor').attr('readonly','readonly');
            $('#scb_qtd_pagamento').focus();
        }
    });

    $('#scb_qtd_pagamento').change(function (e){
        var uni = parseInt($('#scb_qtd_pagamento').val());
        console.log(uni);
        var valor = parseFloat($("#scb_vfp_id").find(":selected").data('valor'));

        var valorFinal = uni * valor;
        valorFinal = valorFinal.toFixed(2);
        $("#scb_valor").val(valorFinal);
    });

});