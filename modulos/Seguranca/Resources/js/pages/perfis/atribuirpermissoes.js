import $ from 'jquery';

$(document).ready(function() {
    // Lógica para marcar/desmarcar todos os itens de uma pasta
    $('.check-all-grupo').on('change', function() {
        var isChecked = $(this).is(':checked');
        // Encontra todos os checkboxes de permissão dentro deste card e aplica o mesmo estado
        $(this).closest('.card').find('.perm-checkbox').prop('checked', isChecked);
    });

    // Lógica para atualizar a caixa "pai" se o usuário clicar individualmente nos "filhos"
    $('.perm-checkbox').on('change', function() {
        var $card = $(this).closest('.card');
        var total = $card.find('.perm-checkbox').length;
        var checked = $card.find('.perm-checkbox:checked').length;

        // Se todos os filhos estiverem marcados, marca o pai. Se não, desmarca o pai.
        $card.find('.check-all-grupo').prop('checked', total === checked && total > 0);

        // (Opcional) Adiciona o estado indeterminado se tiver apenas alguns marcados
        $card.find('.check-all-grupo').prop('indeterminate', checked > 0 && checked < total);
    });

    // Inicializa o estado visual das pastas ao carregar a página
    $('.card').each(function() {
        var total = $(this).find('.perm-checkbox').length;
        var checked = $(this).find('.perm-checkbox:checked').length;

        if (total > 0) {
            if (checked === total) {
                $(this).find('.check-all-grupo').prop('checked', true);
            } else if (checked > 0) {
                $(this).find('.check-all-grupo').prop('indeterminate', true);
            }
        }
    });

    // Intercepta o envio do formulário para agrupar os IDs na vírgula (igual ao código legado)
    $('#formPermissoes').on('submit', function(e) {
        e.preventDefault();

        var checked_ids = [];

        // Varre todos os checkboxes marcados e pega o "value" (que é o ID)
        $('.perm-checkbox:checked').each(function() {
            checked_ids.push($(this).val());
        });

        // Transforma a array em uma string com vírgulas ex: "1,2,5,8"
        $('#permissao').val(checked_ids.join(','));

        // Submete o formulário nativamente
        this.submit();
    });
});