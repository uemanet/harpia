+function ($) {
    'use strict';

    $(".btn-delete").on('click', function(event) {
        event.preventDefault();

        var button = $(this);

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
                button.closest('form').submit();
            }
        });

    });

}(jQuery);

$.harpia = {};

+function ($) {
    'use strict';

    $.harpia.showloading = function() {
        $(".loading").show();
    };

    $.harpia.hideloading = function() {
        $(".loading").hide();
    }
}(jQuery);