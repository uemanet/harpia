import $ from 'jquery';
import { Modal } from 'bootstrap';

$(document).ready(function() {
    $(document).on('click', '.btn-ver-json', function() {
        var logId = $(this).data('id');
        var rawJson = $(this).attr('data-json');

        $('#modal-log-id').text(logId);

        try {
            var parsed = JSON.parse(rawJson);
            $('#json-renderer').text(JSON.stringify(parsed, null, 4));
        } catch (e) {
            $('#json-renderer').text(rawJson);
        }

        var modalElement = document.getElementById('modal-json');
        var modalInstance = Modal.getOrCreateInstance(modalElement);
        modalInstance.show();
    });
});