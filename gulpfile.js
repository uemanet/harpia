var elixir = require("laravel-elixir");

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

    // LESS
    mix.less([
        "font-awesome.less",
        "bootstrap.less",
        "adminLTE.less",
        "skin.less",
        "harpia.less"
    ]);

    // CSS
    mix.copy("node_modules/sweetalert/dist/sweetalert.css", "public/css/plugins");
    mix.copy("node_modules/toastr/build/toastr.min.css", "public/css/plugins");
    mix.copy("node_modules/select2/dist/css/select2.css", "public/css/plugins");
    mix.copy("node_modules/admin-lte/plugins/datepicker/datepicker3.css", "public/css/plugins");

    // JAVASCRIPT
    mix.copy("node_modules/admin-lte/plugins/jQuery/jquery-2.2.3.min.js", "public/js");
    mix.copy("node_modules/bootstrap/dist/js/bootstrap.min.js", "public/js");
    mix.copy("node_modules/admin-lte/dist/js/app.min.js", "public/js");
    mix.copy("resources/assets/js/harpia.js", "public/js");
    mix.copy("node_modules/toastr/build/toastr.min.js", "public/js/plugins");
    mix.copy("node_modules/sweetalert/dist/sweetalert.min.js", "public/js/plugins");
    mix.copy("node_modules/select2/dist/js/select2.js", "public/js/plugins");
    mix.copy("node_modules/admin-lte/plugins/datepicker/bootstrap-datepicker.js", "public/js/plugins");
    mix.copy("node_modules/admin-lte/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js", "public/js/plugins");
    // FONTS
    mix.copy("node_modules/bootstrap/fonts", "public/fonts");
    mix.copy("node_modules/font-awesome/fonts", "public/fonts");

    // Copy iCheck Components
    mix.copy("node_modules/admin-lte/plugins/iCheck/square/blue.css", "public/css/plugins/icheck/icheck.css");
    mix.copy("node_modules/admin-lte/plugins/iCheck/square/blue.png", "public/css/plugins/icheck");
    mix.copy("node_modules/admin-lte/plugins/iCheck/square/blue@2x.png", "public/css/plugins/icheck");
    mix.copy("node_modules/admin-lte/plugins/iCheck/icheck.min.js", "public/js/plugins/icheck/");
});
