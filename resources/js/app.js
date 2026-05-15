import jQuery from 'jquery';
import toastr from 'toastr';
import Swal from 'sweetalert2';

window.$ = window.jQuery = jQuery;
window.toastr = toastr;
window.Swal = window.swal = Swal;

// Importa o Bootstrap 5
import 'bootstrap/dist/js/bootstrap.bundle.min';

// Importa o core do AdminLTE v4
import 'admin-lte';

// Importa o OverlayScrollbars
import { OverlayScrollbars } from 'overlayscrollbars';

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

import 'moment';
import 'inputmask/dist/jquery.inputmask.js';
import 'jquery-validation';

import select2 from 'select2';
select2(window.$);

import 'jstree';

import Chart from 'chart.js/auto';
window.Chart = Chart;

// Flatpickr
import flatpickr from "flatpickr";
import { Portuguese } from "flatpickr/dist/l10n/pt.js";

// Importando arquivos locais
// import './cpfcnpj.min.js';
import 'fullcalendar';
import './harpia.js';

document.addEventListener('DOMContentLoaded', function () {
// $(document).ready(function() {
    $("select").select2({
        theme: 'bootstrap-5'
    });

    // Inicialização do Flatpickr
    flatpickr(".datepicker, .only-date", {
        locale: Portuguese,
        dateFormat: "d/m/Y",
        allowInput: true,
    });

    flatpickr(".datepicker_us", {
        dateFormat: "Y-m-d",
        allowInput: true,
    });

    flatpickr(".only-time", {
        locale: Portuguese,
        enableTime: true,
        time_24hr: true,
        noCalendar: true,
        dateFormat: "H:i",
        allowInput: true,
    });

    flatpickr(".datetime", {
        locale: Portuguese,
        enableTime: true,
        time_24hr: true,
        dateFormat: "d/m/Y H:i",
        allowInput: true,
    });

    $('.cpf-mask').inputmask({
        mask: "999.999.999-99"
    });
});