const mix = require('laravel-mix')

mix.js('resources/assets/js/app.js', 'public/js');

mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.copy('resources/assets/js/bulma.js', 'public/js');
mix.js('resources/assets/js/open-api-viewer.js', 'public/js/docs.js')

if (mix.config.inProduction) {
	module.exports = { mode: 'production' };
	mix.version();
}

mix.browserSync('https://dbp.test');
