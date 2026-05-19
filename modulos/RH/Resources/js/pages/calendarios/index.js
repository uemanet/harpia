import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import ptBrLocale from '@fullcalendar/core/locales/pt-br';
import timeGridPlugin from '@fullcalendar/timegrid';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formEvent');
    const calendarElement = document.getElementById('calendar');
    const newButton = document.getElementById('btnNovo');
    const saveButton = document.getElementById('btnSalvar');
    const deleteButton = document.getElementById('btnExcluir');

    if (!form || !calendarElement || !newButton || !saveButton || !deleteButton) {
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
            toastr.error(message, null, { progressBar: true });
            return;
        }

        window.alert(message);
    };

    const toggleDeleteButton = (eventId = '') => {
        deleteButton.dataset.id = eventId;
        deleteButton.classList.toggle('d-none', !eventId);
    };

    const resetForm = () => {
        form.reset();
        fields.id.value = '';
        saveButton.disabled = false;
        saveButton.textContent = 'Salvar';
        toggleDeleteButton();
        fields.type.focus();
    };

    const getErrorMessage = async (response, fallbackMessage) => {
        try {
            const payload = await response.json();

            if (payload?.message) {
                return payload.message;
            }

            const validationBag = payload?.errors ?? payload;
            const validationErrors = validationBag && typeof validationBag === 'object'
                ? Object.values(validationBag).flat()
                : [];

            if (validationErrors.length > 0) {
                return validationErrors[0];
            }
        } catch (error) {
            // Mantem a mensagem padrao quando o backend nao retorna JSON.
        }

        return fallbackMessage;
    };

    const requestJson = async (url, options = {}, fallbackMessage = 'Erro ao processar a requisição.') => {
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                ...options.headers,
            },
            ...options,
        });

        if (!response.ok) {
            throw new Error(await getErrorMessage(response, fallbackMessage));
        }

        if (response.status === 204) {
            return null;
        }

        return response.json();
    };

    const calendar = new Calendar(calendarElement, {
        plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
        locale: ptBrLocale,
        initialView: 'dayGridMonth',
        firstDay: 0,
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia',
        },
        events: async (_fetchInfo, successCallback, failureCallback) => {
            try {
                const data = await requestJson(urls.events, {}, 'Não foi possível carregar os eventos do calendário.');

                successCallback((data ?? []).map((event) => ({
                    allDay: true,
                    id: String(event.cld_id),
                    start: event.cld_data,
                    title: event.cld_nome,
                })));
            } catch (error) {
                notifyError(error.message);
                failureCallback(error);
            }
        },
        eventClick: async ({ event }) => {
            showLoading();

            try {
                const data = await requestJson(
                    urls.editTemplate.replace('__ID__', event.id),
                    {},
                    'Não foi possível carregar o evento selecionado.'
                );

                fields.id.value = data.cld_id ?? '';
                fields.name.value = data.cld_nome ?? '';
                fields.type.value = data.cld_tipo_evento ?? '';
                fields.date.value = data.cld_data ?? '';
                fields.notes.value = data.cld_observacao ?? '';
                saveButton.textContent = 'Alterar';
                toggleDeleteButton(String(data.cld_id ?? ''));
                fields.type.focus();
            } catch (error) {
                notifyError(error.message);
            } finally {
                hideLoading();
            }
        },
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!form.reportValidity()) {
            return;
        }

        saveButton.disabled = true;
        showLoading();

        const payload = new URLSearchParams({
            cld_id: fields.id.value,
            cld_data: fields.date.value,
            cld_nome: fields.name.value,
            cld_tipo_evento: fields.type.value,
            cld_observacao: fields.notes.value,
            _token: csrfToken,
        });

        try {
            await requestJson(urls.save, {
                body: payload,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                },
                method: 'POST',
            }, 'Não foi possível salvar o evento.');

            resetForm();
            calendar.refetchEvents();
        } catch (error) {
            notifyError(error.message);
        } finally {
            hideLoading();
            saveButton.disabled = false;
        }
    });

    deleteButton.addEventListener('click', async () => {
        const eventId = deleteButton.dataset.id || fields.id.value;

        if (!eventId) {
            return;
        }

        showLoading();

        try {
            await requestJson(urls.delete, {
                body: new URLSearchParams({
                    id: eventId,
                    _token: csrfToken,
                }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                },
                method: 'POST',
            }, 'Não foi possível excluir o evento.');

            resetForm();
            calendar.refetchEvents();
        } catch (error) {
            notifyError(error.message);
        } finally {
            hideLoading();
        }
    });

    newButton.addEventListener('click', resetForm);

    resetForm();
    calendar.render();
});
