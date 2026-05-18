import $ from 'jquery';
import Swal from "sweetalert2";

$(document).on('click', '.btn-success', function (event) {
    event.preventDefault();

    var button = $(this);

    Swal.fire({
        title: "Tem certeza que deseja confirmar as férias do colaborador?",
        text: "Essa alteração é irreversível!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        closeOnConfirm: true
    }, function(isConfirm){
        if (isConfirm) {
            button.closest("form").submit();
        }
    });
});