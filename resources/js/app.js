// Import jQuery primeiro (para manter compatibilidade com seus plugins antigos)
window.$ = window.jQuery = require('jquery');
window.toastr = require('toastr');
window.Swal = require('sweetalert2');
window.swal = window.Swal;
// Importa o Bootstrap 5 (versão bundle já inclui o Popper para os dropdowns funcionarem)
require('bootstrap/dist/js/bootstrap.bundle.min');

// Importa o core do AdminLTE v4
require('admin-lte');

// Importa o OverlayScrollbars (Fundamental para o layout-fixed da v4 não quebrar)
const { OverlayScrollbars } = require('overlayscrollbars');

// Inicialização obrigatória do OverlayScrollbars no Sidebar (padrão v4)
document.addEventListener('DOMContentLoaded', function () {
    const sidebarWrapper = document.querySelector('.sidebar-wrapper');
    if (sidebarWrapper) {
        OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
                theme: 'os-theme-light',
                autoHide: 'leave',
                clickScroll: true,
            },
        });
    }
});

require('moment');
require('inputmask/dist/jquery.inputmask.js');

require('jquery-validation');
require('select2');

require('icheck');
require('jstree');

const flatpickr = require("flatpickr");
const { Portuguese } = require("flatpickr/dist/l10n/pt");

require('./Chart.js'); // TODO: verificar caminho
require('./cpfcnpj.min.js'); // TODO: verificar caminho
require('fullcalendar');
require('./harpia.js'); // TODO: verificar caminho

$(document).ready(function() {

    $("select").select2({
        theme: 'bootstrap-5'
    });

    // Inicializa todos os dropdowns bootstrap
    // $('.dropdown-toggle').dropdown();

    // Inicializa os dropdowns de ação na tabela
    // $(document).on('click', '.btn-group .dropdown-toggle', function() {
    //     $(this).siblings('.dropdown-menu').toggle();
    // });

    // Inicialização do Flatpickr (Substitui o bootstrap-datepicker e o jquery-datetimepicker)
    // Aplica simultaneamente para as classes .datepicker e .only-date
    $(".datepicker").flatpickr({
        locale: Portuguese,
        dateFormat: "d/m/Y",
        allowInput: true,
    });

    $(".only-date").flatpickr({
        locale: Portuguese,
        dateFormat: "d/m/Y",
        allowInput: true,
    });

    $(".only-time").flatpickr({
        locale: Portuguese,
        enableTime: true,
        time_24hr: true,
        noCalendar: true,
        dateFormat: "H:i",
        allowInput: true,
    });

    $(".datetime").flatpickr({
        locale: Portuguese,
        enableTime: true,
        time_24hr: true,
        dateFormat: "d/m/Y H:i",
        allowInput: true,
    });

    $('.cpf-mask').inputmask({
        mask: "999.999.999-99",
        removeMaskOnSubmit: true,
    });

});