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

    // CSS
    //mix.copy('node_modules/admin-lte/bootstrap/css/bootstrap.min.css', 'public/css');

    // JAVASCRIPT
    mix.copy('node_modules/admin-lte/plugins/jQuery/jQuery-2.2.0.min.js', 'public/javascript');
    mix.copy('node_modules/bootstrap/dist/js/bootstrap.min.js', 'public/javascript');
    mix.copy('node_modules/admin-lte/dist/js/app.min.js', 'public/javascript');

    // FONTS
    mix.copy('node_modules/bootstrap/fonts', 'public/fonts');
});
