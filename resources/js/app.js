import jQuery from 'jquery';

// DEVE vir antes de qualquer plugin jQuery
window.$ = jQuery;
window.jQuery = jQuery;

import toastr from 'toastr';
import Swal from 'sweetalert2';

window.toastr = toastr;
window.Swal = window.swal = Swal;

import 'bootstrap/dist/js/bootstrap.bundle.min';
import 'admin-lte';

import { OverlayScrollbars } from 'overlayscrollbars';

import 'moment';
import 'inputmask/dist/jquery.inputmask.js';
import 'jquery-validation';

import select2 from 'select2';
select2(window.$);

import 'jstree';

import Chart from 'chart.js/auto';
window.Chart = Chart;

import flatpickr from "flatpickr";
import { Portuguese } from "flatpickr/dist/l10n/pt.js";

import 'fullcalendar';
import './harpia.js';

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

    flatpickr(".datetime-full", {
        locale: Portuguese,
        enableTime: true,
        time_24hr: true,
        dateFormat: "d/m/Y H:i:S",
        allowInput: true,
    });

    $('.cpf-mask').inputmask({
        mask: "999.999.999-99",
        removeMaskOnSubmit: true
    });

    $('.cnpj-mask').inputmask({
        mask: "99.999.999/9999-99",
        removeMaskOnSubmit: true
    });

    $('.cep-mask').inputmask({
        mask: "99.999-999",
        removeMaskOnSubmit: true
    });
});