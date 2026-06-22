import $ from 'jquery';
import moment from 'moment';
import { Calendar } from 'fullcalendar';

const csrfToken = $('meta[name="csrf-token"]').attr('content');

let calendarInstance = null;

$(function () {
    const form = document.getElementById('formEvent');

    if (!form) {
        return;
    }

    $('#btnNovo').on('click', function () {
        clearForm();
    });

    $('#formEvent').validate({
        rules: {
            cld_nome: { required: true, maxlength: 80 },
            cld_tipo_evento: { required: true },
            cld_data: { required: true },
            cld_observacao: { maxlength: 255 }
        },
        messages: {
            cld_nome: { required: 'Campo obrigatório' },
            cld_tipo_evento: { required: 'Campo obrigatório' },
            cld_data: { required: 'Campo obrigatório' }
        },
        submitHandler: function (_form, event) {
            if (event) {
                event.preventDefault();
            }

            saveEvent();
        }
    });

    getEventsData();
});

function getEventsData() {
    $.ajax({
        url: window.PageRoutes.calendarios_index,
        type: "GET",
        success: function (data) {
            const eventos = data.map(function (objeto) {
                const dataFormatada = moment(objeto.cld_data, [
                    "YYYY-MM-DD HH:mm:ss",
                    "DD/MM/YYYY HH:mm",
                    "DD/MM/YYYY",
                    "YYYY-MM-DD"
                ], true);

                return {
                    id: objeto.cld_id,
                    title: objeto.cld_nome,
                    start: dataFormatada.isValid() ? dataFormatada.format("YYYY-MM-DD") : objeto.cld_data,
                    allDay: true
                };
            });

            renderCalendar(eventos);
        },
        error: function () {
            notifyError("Erro ao carregar os eventos.");
        }
    });
}

function renderCalendar(data) {
    const calendarEl = document.getElementById('calendar');

    if (!calendarEl) {
        return;
    }

    if (calendarInstance) {
        calendarInstance.removeAllEvents();
        calendarInstance.addEventSource(data);
        return;
    }

    calendarInstance = new Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia'
        },
        locale: 'pt-br',
        events: data,
        eventDidMount: function (info) {
            const editWrapper = document.createElement('span');
            editWrapper.className = 'closeon';
            editWrapper.style.cssText = 'position: absolute; right: 2px; top: 2px; cursor: pointer; z-index: 99; color: inherit;';
            editWrapper.innerHTML = "<i class='fa fa-edit'></i>";

            editWrapper.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                editEvent(info.event.id);
            });

            const targetNode = info.el.querySelector('.fc-event-main') ||
                info.el.querySelector('.fc-content') ||
                info.el.querySelector('.fc-event-title-container') ||
                info.el;

            targetNode.appendChild(editWrapper);
        }
    });

    calendarInstance.render();
}

function saveEvent() {
    const data = {
        cld_id: $('#cld_id').val(),
        cld_data: $('#cld_data').val(),
        cld_nome: $('#cld_nome').val(),
        cld_tipo_evento: $('#cld_tipo_evento').val(),
        cld_observacao: $('#cld_observacao').val(),
        _token: csrfToken,
    };

    $('#btnSalvar').prop('disabled', true);
    showLoading();

    $.ajax({
        type: 'POST',
        url: window.PageRoutes.calendarios_create,
        data: data,
        success: function () {
            hideLoading();
            clearForm();
            getEventsData();
        },
        error: function (err) {
            hideLoading();
            notifyError(err?.responseJSON?.message || 'Erro ao salvar o evento.');
            $('#btnSalvar').prop('disabled', false);
        }
    });
}

function editEvent(id) {
    showLoading();

    $.ajax({
        url: buildEditUrl(id),
        type: "GET",
        success: function (data) {
            hideLoading();
            renderDeleteButton(data.cld_id);

            $('#cld_id').val(data.cld_id);
            $('#cld_nome').val(data.cld_nome);
            $('#cld_tipo_evento').val(data.cld_tipo_evento);
            $('#cld_observacao').val(data.cld_observacao);
            $('#cld_data').val(data.cld_data);
            $('#btnSalvar').html('Alterar');
        },
        error: function () {
            hideLoading();
            notifyError("Erro ao buscar dados do evento.");
        }
    });
}

function removeEvent(id) {
    let data = {
        id: id,
        _token: csrfToken,
    };

    showLoading();

    $.ajax({
        type: "POST",
        url: window.PageRoutes.calendarios_delete,
        data: data,
        success: function () {
            hideLoading();
            getEventsData();
            clearForm();
        },
        error: function (err) {
            hideLoading();
            notifyError(err?.responseJSON?.message || 'Erro ao excluir o evento.');
        }
    });
}

function clearForm() {
    $('#btnExcluir').remove();
    $('#cld_id').val('');
    $('#cld_nome').val('');
    $('#cld_tipo_evento').val('').focus(); // Typo corrigido aqui
    $('#cld_observacao').val('');
    $('#cld_data').val('');
    $('#btnSalvar').prop("disabled", false);
    $('#btnSalvar').html('Salvar');
}

function renderDeleteButton(id) {
    $('#btnExcluir').remove();
    $('#footerForm').append('<button class="btn btn-danger" type="button" id="btnExcluir" data-id="' + id + '">Excluir</button>');

    $('#btnExcluir').on('click', function (event) {
        const itemSelecionado = $(event.currentTarget).data('id');
        removeEvent(itemSelecionado);
    });
}

function buildEditUrl(id) {
    return window.PageRoutes.calendarios_edit.replace('__ID__', id);
}

function showLoading() {
    $.harpia?.showloading?.();
}

function hideLoading() {
    $.harpia?.hideloading?.();
}

function notifyError(message) {
    if (window.toastr?.error) {
        window.toastr.error(message, null, {progressBar: true});
    }
}
