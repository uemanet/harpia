const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .version();

// Se você precisar de múltiplos arquivos CSS/JS, pode adicionar aqui:
// mix.js('resources/js/admin.js', 'public/js')
//    .sass('resources/sass/admin.scss', 'public/css');

// Copiar assets necessários
mix.copy('node_modules/font-awesome/fonts', 'public/fonts');