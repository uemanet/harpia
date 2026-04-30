import $ from 'jquery';
import Swal from 'sweetalert2';

// 1. O bloco do botão de Exclusão (SweetAlert)
$(document).on('click', '.btn-delete', function (event) {
    event.preventDefault();

    var button = $(this);

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
            button.closest("form").submit();
        }
    });
});

// 2. O bloco das requisições e loading da aplicação
$.harpia = {};

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

    return $.ajax({
        url: url,
        type: "POST",
        data: data
    })
        .done(function(resp, textStatus, xhr){
            $.harpia.hideloading();

            return {
                ok: true,
                status: xhr.status,
                data: resp
            };
        })
        .fail(function(xhr){
            $.harpia.hideloading();

            return {
                ok: false,
                status: xhr.status,
                data: xhr.responseText,
                xhr: xhr
            };
        });
}

document.addEventListener('DOMContentLoaded', function () {
    // Verifica se há alguma mensagem enviada pela sessão do PHP
    if (window.HarpiaFlashMessages && window.HarpiaFlashMessages.notifications) {

        let defaultConfig = window.HarpiaFlashMessages.defaultConfig || {};

        window.HarpiaFlashMessages.notifications.forEach(function(notification) {

            // Mescla as configurações padrão com as opções específicas da notificação
            let finalConfig = Object.assign({}, defaultConfig, notification.options || {});

            // Usa a instância global do toastr que você declarou no app.js
            window.toastr.options = finalConfig;

            // Dispara o alerta (success, error, warning, info)
            window.toastr[notification.type](notification.message, notification.title);
        });
    }
});