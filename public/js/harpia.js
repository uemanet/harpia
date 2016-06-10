+function ($) {
    'use strict';

    $(".btn-delete").on('click', function(event) {
        event.preventDefault();

        var button = $(this);
        
        button.closest('form').submit();
    });

}(jQuery);