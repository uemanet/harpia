+function ($) {
    'use strict';

    $(".btn-delete").on("click", function(event) {
        event.preventDefault();

        var button = $(this);

        swal({
            title: "Tem certeza que deseja excluir?",
            text: "Você não poderá recuperar essa informação!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, pode excluir!",
            cancelButtonText: "Não, quero cancelar!",
            closeOnConfirm: true
        }, function(isConfirm){
            if (isConfirm) {
                button.closest("form").submit();
            }
        });

    });

    $(document).on('click', '.sidebar-left li a', function (e) {
        //Get the clicked link and the next element
        //console.log(menu);
        var $this = $(this);
        var checkElement = $this.next();
        var animationSpeed = 500;

        //Check if the next element is a menu and is visible
        if ((checkElement.is('.treeview-menu')) && (checkElement.is(':visible')) && (!$('body').hasClass('sidebar-collapse'))) {
            //Close the menu
            checkElement.slideUp(animationSpeed, function () {
                checkElement.removeClass('menu-open');
            });
            checkElement.parent("li").removeClass("active");
        }
        else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
            //Get the parent menu
            var parent = $this.parents('ul').first();
            //Close all open menus within the parent
            var ul = parent.find('ul:visible').slideUp(animationSpeed);
            //Remove the menu-open class from the parent
            ul.removeClass('menu-open');
            //Get the parent li
            var parent_li = $this.parent("li");

            //Open the target menu and add the menu-open class
            checkElement.slideDown(animationSpeed, function () {
                //Add the class active to the parent li
                checkElement.addClass('menu-open');
                //parent.find('li.active').removeClass('active');
                parent_li.addClass('active');
            });
        }
        if (checkElement.is('.treeview-menu')) {
            e.preventDefault();
        }
    });

}(jQuery);

$.harpia = {};

+function ($) {
    'use strict';

    $.harpia.showloading = function() {
        var html = "<div id='loading-overlay' class='loading-lockscreen'></div>"+
                   "<div id='loading-message' class='loading-lockscreen'>"+
                     "<p>Carregando...</p>"+
                     "<div class='three-quarters'></div>"+
                   "</div>";

        $("html").append(html);
    };

    $.harpia.hideloading = function() {
        $(".loading-lockscreen").remove();
    };

    $.harpia.httpget = function(url) {

        $.harpia.showloading();

        var result = false;

        return $.ajax({
            url: url,
            type: "GET",
            success: function(resp) {

                $.harpia.hideloading();

                result = resp;
            },
            error: function(e) {
                $.harpia.hideloading();

                sweetAlert("Oops...", "Algo estranho aconteceu! Se o problema persistir, entre em contato com a administração do sistema.", "error");

                result = false;
            }
        }).then(function() {
            return $.Deferred(function(def) {
                def.resolveWith({},[result]);
            }).promise();
        });
    };
}(jQuery);
