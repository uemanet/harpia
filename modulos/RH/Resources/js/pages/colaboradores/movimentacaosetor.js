import $ from 'jquery';
import Swal from "sweetalert2";

$(document).on('click', '.btn-desvincular', function (event) {
    event.preventDefault();

    const button = $(this);

    Swal.fire({
        title: "Tem certeza que deseja desvincular a função?",
        text: "Esta operação não poderá ser desfeita!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Sim, pode excluir!",
        cancelButtonText: "Não, quero cancelar!"
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest("form").trigger('submit');
        }
    });
});
