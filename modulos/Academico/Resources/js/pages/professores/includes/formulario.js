import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function (){

    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        language: 'pt-BR',
        autoclose: true
    });

    $('#doc_conteudo').inputmask({"mask": "999.999.999-99", "removeMaskOnSubmit": true});
    $('#pes_telefone').inputmask({"mask": "(99) 99999-9999", "removeMaskOnSubmit": true});
    $('#pes_cep').inputmask({"mask": "99999-999", "removeMaskOnSubmit": true});

    $("#pes_cep").focusout(function(e){

        function limpaFormCep() {

            $("#pes_cidade").val("");
            $("#pes_estado").val("");
            $("#pes_bairro").val("");
            $("#pes_endereco").val("");
        }

        var str = e.target.value;

        var cep = str.replace(/\D/g, '');

        if (str != "") {
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            if(validacep.test(cep)) {

                $("#pes_cidade").val("Buscando...");
                $("#pes_estado").val("Buscando...");
                $("#pes_bairro").val("Buscando...");
                $("#pes_endereco").val("Buscando...");

                $.harpia.httpget('https://viacep.com.br/ws/' + cep + '/json/').done(function (data) {
                    if (!data.erro) {
                        $("#pes_cidade").val(data.localidade);
                        $("#pes_estado").val(data.uf).change();
                        $("#pes_bairro").val(data.bairro);
                        $("#pes_endereco").val(data.logradouro);
                    } else {
                        limpaFormCep();
                        toastr.error("CEP não encontrado", null, {progressBar: true});
                    }
                });
            } else {
                limpaFormCep();
                toastr.warning("Formato do CEP inválido", null, {progressBar: true});
            }
        } else {
            limpaFormCep();
        }
    });
});