const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.js('resources/assets/js/main.js', 'public/js/app.js');
//mix.js('resources/assets/js/swagger.js', 'public/js/swagger.js');

mix.browserSync({
    proxy: 'https://dbp.localhost'
});