import $ from 'jquery';
import Swal from "sweetalert2";

$(document).on('click', '.btn-confirmar-ferias', function (event) {
    event.preventDefault();

    const button = $(this);

    Swal.fire({
        title: "Tem certeza que deseja confirmar as férias do colaborador?",
        text: "Essa alteração é irreversível!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#198754",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Sim",
        cancelButtonText: "Não"
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest("form").trigger('submit');
        }
    });
});
