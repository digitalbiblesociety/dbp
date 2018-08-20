const mix = require('laravel-mix')
const request = require("request")
const fs = require('fs')

// Prep and Generate documentation
request({ url: "https://dbp.test/swagger_docs?v=2", json: true, strictSSL:false }, function (error, response, body) {
	fs.writeFile('resources/assets/js/swagger_v2.json', JSON.stringify(body), 'utf8', function () {
		mix.js('resources/assets/js/swagger-vue-v2.js', 'public/js/swagger-vue-v2.js')
	});
})
request({ url: "https://dbp.test/swagger_docs", json: true, strictSSL:false }, function (error, response, body) {
	fs.writeFile('resources/assets/js/swagger_v4.json', JSON.stringify(body), 'utf8', function () {
		mix.js('resources/assets/js/swagger-vue-v4.js', 'public/js/swagger-vue-v4.js')
	});
})

mix.js('resources/assets/js/app.js', 'public/js');

mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.copy('resources/assets/js/bulma.js', 'public/js');

if (mix.config.inProduction) mix.version();

mix.browserSync('https://dbp.test');
