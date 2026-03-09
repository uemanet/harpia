+function ($) {
    'use strict';

    $(document).on('click', '.btn-delete', function (event) {
        event.preventDefault();

        var button = $(this);

        Swal.fire({
            title: "Tem certeza que deseja excluir?",
            text: "Você não poderá recuperar essa informação!",
            icon: "warning", // 'type' foi alterado para 'icon' no SweetAlert2
            showCancelButton: true,
            confirmButtonColor: "#dc3545", // Cor vermelha atualizada para o padrão danger do BS5
            cancelButtonColor: "#6c757d",  // Cor secundária do BS5 para o botão cancelar
            confirmButtonText: "Sim, pode excluir!",
            cancelButtonText: "Não, quero cancelar!"
            // 'closeOnConfirm' já não é necessário no SweetAlert2
        }).then((result) => {
            // Nova sintaxe com Promises substitui a antiga function(isConfirm)
            if (result.isConfirmed) {
                button.closest("form").submit();
            }
        });
    });

}(jQuery);

$.harpia = {};

+function ($) {
    'use strict';

    $.harpia.showloading = function() {
        var html = "<div id='loading-overlay' class='loading-lockscreen'></div>"+
            "<div id='loading-message' class='loading-lockscreen'>"+
            "<p>Carregando...</p>"+
            "<div class='three-quarters'></div>"+
            "</div>";

        $("html").append(html);
    };

    $.harpia.hideloading = function() {
        $(".loading-lockscreen").remove();
    };

    $.harpia.httpget = function(url) {

        $.harpia.showloading();

        var result = false;

        return $.ajax({
            url: url,
            type: "GET",
            success: function(resp) {

                $.harpia.hideloading();

                result = resp;
            },
            error: function(e) {
                $.harpia.hideloading();

                // sweetAlert substituído por Swal.fire
                Swal.fire("Oops...", "Algo estranho aconteceu! Se o problema persistir, entre em contato com a administração do sistema.", "error");

                result = false;
            }
        }).then(function() {
            return $.Deferred(function(def) {
                def.resolveWith({},[result]);
            }).promise();
        });
    };

    $.harpia.httppost = function(url, data) {

        $.harpia.showloading();

        var result = false;

        return $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function(resp) {

                $.harpia.hideloading();

                result = resp;
            },
            error: function(e) {
                $.harpia.hideloading();

                // sweetAlert substituído por Swal.fire
                Swal.fire("Oops...", "Algo estranho aconteceu! Se o problema persistir, entre em contato com a administração do sistema.", "error");

                result = false;
            }
        }).then(function() {
            return $.Deferred(function(def) {
                def.resolveWith({},[result]);
            }).promise();
        });
    };

}(jQuery);