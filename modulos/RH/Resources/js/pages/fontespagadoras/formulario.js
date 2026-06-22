import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function (){
    $("#fpg_cep").focusout(function(e){
        console.log('teste')
        function limpaFormCep() {

            $("#fpg_cidade").val("");
            $("#fpg_uf").val("");
            $("#fpg_bairro").val("");
            $("#fpg_endereco").val("");
        }

        var str = e.target.value;

        var cep = str.replace(/\D/g, '');

        if (str != "") {
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            if(validacep.test(cep)) {

                $("#fpg_cidade").val("Buscando...");
                $("#fpg_uf").val("Buscando...");
                $("#fpg_bairro").val("Buscando...");
                $("#fpg_endereco").val("Buscando...");

                $.harpia.httpget('https://viacep.com.br/ws/' + cep + '/json/').done(function (data) {
                    if (!data.erro) {
                        $("#fpg_cidade").val(data.localidade);
                        $("#fpg_uf").val(data.uf).change();
                        $("#fpg_bairro").val(data.bairro);
                        $("#fpg_endereco").val(data.logradouro);
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