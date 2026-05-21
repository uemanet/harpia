import $ from 'jquery';
import { Calendar } from 'fullcalendar';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

let calendarInstance = null; // Variável global para reaproveitar o calendário

$(document).ready(function () {
    $("#formEvent").validate({
        // Rules for form validation
        rules: {
            cld_nome: { required: true, maxlength: 100 },
            cld_tipo_evento: { required: true },
            cld_data: { required: true },
            cld_observacao: { required: false }
        },
        // Messages for form validation
        messages: {
            cld_nome: { required: 'Campo obrigatório' },
            cld_tipo_evento: { required: 'Campo obrigatório' },
            cld_data: { required: 'Campo obrigatório' },
            cld_observacao: { required: 'Campo obrigatório' }
        },
        submitHandler: function(form, e) {
            e.preventDefault();

            let data = {
                cld_id: $('#cld_id').val(),
                cld_data: $('#cld_data').val(),
                cld_nome: $('#cld_nome').val(),
                cld_tipo_evento: $('#cld_tipo_evento').val(),
                cld_observacao: $('#cld_observacao').val(),
                _token: csrfToken,
            };

            $('#btnSalvar').prop('disabled', true); // Bloqueia o botão

            // Ajax request
            $.harpia.showloading();
            $.ajax({
                type: "POST",
                url: window.PageRoutes.calendarios_create,
                data: data,
                success: function (response) {
                    $.harpia.hideloading();
                    clearForm(); // Limpa form após salvar
                    getEventsData();
                },
                error: function (err) {
                    $.harpia.hideloading();
                    toastr.error(err.responseJSON.message, null, {progressBar: true});
                    $('#btnSalvar').prop('disabled', false); // Reabilita o botão
                }
            });
        }
    });

    getEventsData();
});

import moment from 'moment';

function getEventsData() {
    $.ajax({
        url: window.PageRoutes.calendarios_index,
        type: "GET",
        success: function (data) {
            let eventos = data.map(function(objeto) {
                // Tenta converter a data que vem do banco para o padrão ISO que o FullCalendar exige
                // Se o seu banco devolve 'Y-m-d H:i:s' (ex: 2026-05-18 15:30:00), o moment formata para o T separador.
                // Se devolver d/m/Y, precisaremos avisar o moment do formato de entrada.

                let dataFormatada = moment(objeto.cld_data, [
                    "YYYY-MM-DD HH:mm:ss",
                    "DD/MM/YYYY HH:mm",
                    "DD/MM/YYYY",
                    "YYYY-MM-DD"
                ]).format("YYYY-MM-DDTHH:mm:ss");

                return {
                    id: objeto.cld_id,
                    title: objeto.cld_nome,
                    start: dataFormatada // Usa a data higienizada!
                };
            });
            renderCalendar(eventos);
        },
        error: function (error) {
            toastr.error("Erro ao carregar os eventos.", null, {progressBar: true});
        }
    });
}

function renderCalendar(data) {
    let calendarEl = document.getElementById('calendar');

    // Se a instância já existir, apenas atualize a fonte de dados (melhora performance)
    if (calendarInstance) {
        calendarInstance.removeAllEvents();
        calendarInstance.addEventSource(data);
        return;
    }

    const fields = {
        id: document.getElementById('cld_id'),
        name: document.getElementById('cld_nome'),
        type: document.getElementById('cld_tipo_evento'),
        date: document.getElementById('cld_data'),
        notes: document.getElementById('cld_observacao'),
    };

    const urls = {
        events: form.dataset.eventsUrl,
        save: form.dataset.saveUrl,
        editTemplate: form.dataset.editUrlTemplate,
        delete: form.dataset.deleteUrl,
    };

    const harpia = window.$?.harpia;
    const toastr = window.toastr;

    const showLoading = () => harpia?.showloading?.();
    const hideLoading = () => harpia?.hideloading?.();

    const notifyError = (message) => {
        if (toastr?.error) {
            toastr.error(message, null, {progressBar: true});
            return;
        }
    }

    // Inicializa o calendário com a API Moderna (v5/v6)
    calendarInstance = new Calendar(calendarEl, {
        initialView: 'dayGridMonth', // antigo defaultView
        headerToolbar: { // antigo header
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay' // Os nomes das views mudaram
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia'
        },
        locale: 'pt-br', // Já deixa em português
        events: data,

        eventDidMount: function (info) {
            // Cria o botão de fechar/editar
            let editWrapper = document.createElement('span');
            editWrapper.className = 'closeon';
            editWrapper.style.cssText = 'position: absolute; right: 2px; top: 2px; cursor: pointer; z-index: 99; color: inherit;';
            editWrapper.innerHTML = "<i class='fa fa-edit'></i>";

            // Adiciona o listener de clique
            editWrapper.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation(); // Evita que clique conflite com as ações do calendário
                editEvent(info.event.id);
            });

            // Busca o melhor lugar para anexar o ícone dependendo da versão do FullCalendar
            let targetNode = info.el.querySelector('.fc-event-main') ||
                info.el.querySelector('.fc-content') ||
                info.el.querySelector('.fc-event-title-container') ||
                info.el;

            // Anexa o ícone
            targetNode.appendChild(editWrapper);
        }
    });

    calendarInstance.render();
}

function editEvent(id) {
    $.harpia.showloading();
    $.ajax({
        url: "async/calendarios/edit/" + id,
        type: "GET",
        success: function (data) {
            $.harpia.hideloading();

            $('#btnExcluir').remove();
            $('#footerForm').append('<button class="btn btn-danger" type="button" id="btnExcluir" data-id="' + data.cld_id + '" >Excluir</button>');

            $('#btnExcluir').click(function (e) {
                var itemSelecionado = $(e.currentTarget).data('id');
                removeEvent(itemSelecionado);
            });

            $('#cld_id').val(data.cld_id);
            $('#cld_nome').val(data.cld_nome);
            $('#cld_tipo_evento').val(data.cld_tipo_evento);
            $('#cld_observacao').val(data.cld_observacao);
            $('#cld_data').val(data.cld_data);
            $('#btnSalvar').html('Alterar');
        },
        error: function (error) {
            $.harpia.hideloading();
            toastr.error("Erro ao buscar dados do evento.", null, {progressBar: true});
        }
    });
}

function removeEvent(id) {
    let data = {
        id: id,
        _token: csrfToken,
    };

    $.harpia.showloading();
    $.ajax({
        type: "POST",
        url: window.PageRoutes.calendarios_delete,
        data: data,
        success: function (response) {
            $.harpia.hideloading();
            getEventsData();
            clearForm();
        },
        error: function (err) {
            $.harpia.hideloading();
            toastr.error(err.responseJSON.message, null, {progressBar: true});
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

$('#btnNovo').click(function (e) {
    clearForm();
});