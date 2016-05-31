var elixir = require('laravel-elixir');

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
        'bootstrap.less',
        'adminLTE.less',
        'skin.less'
    ]);

    // JAVASCRIPT
    mix.copy('node_modules/admin-lte/plugins/jQuery/jQuery-2.2.0.min.js', 'public/js');
    mix.copy('node_modules/bootstrap/dist/js/bootstrap.min.js', 'public/js');
    mix.copy('node_modules/admin-lte/dist/js/app.min.js', 'public/js');

    // FONTS
    mix.copy('node_modules/bootstrap/fonts', 'public/fonts');

    // Copy iCheck Components
    mix.copy('node_modules/admin-lte/plugins/iCheck/square/blue.css', 'public/css/plugins/icheck/icheck.css');
    mix.copy('node_modules/admin-lte/plugins/iCheck/square/blue.png', 'public/css/plugins/icheck');
    mix.copy('node_modules/admin-lte/plugins/iCheck/square/blue@2x.png', 'public/css/plugins/icheck');
    mix.copy('node_modules/admin-lte/plugins/iCheck/icheck.min.js', 'public/js/plugins/icheck/');
});
