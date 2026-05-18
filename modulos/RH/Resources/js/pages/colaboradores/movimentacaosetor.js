import $ from 'jquery';
import Swal from "sweetalert2";

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).on('click', '.btn-desvincular', function (event) {
    event.preventDefault();

    var button = $(this);

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
            console.log('teste')
            button.closest("form").submit();
        }
    });
});