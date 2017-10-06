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

var foundationJsFolder = '../../../node_modules/foundation-sites/js/';
mix.combine([
    'resources/assets/js/main.js',
    foundationJsFolder + 'foundation.core.js',
    foundationJsFolder + 'foundation.abide.js',
    foundationJsFolder + 'foundation.accordion.js',
    foundationJsFolder + 'foundation.accordionMenu.js',
    foundationJsFolder + 'foundation.drilldown.js',
    foundationJsFolder + 'foundation.dropdown.js',
    foundationJsFolder + 'foundation.dropdownMenu.js',
    foundationJsFolder + 'foundation.equalizer.js',
    foundationJsFolder + 'foundation.interchange.js',
    foundationJsFolder + 'foundation.magellan.js',
    foundationJsFolder + 'foundation.offcanvas.js',
    foundationJsFolder + 'foundation.orbit.js',
    foundationJsFolder + 'foundation.plugin.js',
    foundationJsFolder + 'foundation.positionable.js',
    foundationJsFolder + 'foundation.responsiveAccordionTabs.js',
    foundationJsFolder + 'foundation.responsiveMenu.js',
    foundationJsFolder + 'foundation.responsiveToggle.js',
    foundationJsFolder + 'foundation.reveal.js',
    foundationJsFolder + 'foundation.slider.js',
    foundationJsFolder + 'foundation.smoothScroll.js',
    foundationJsFolder + 'foundation.sticky.js',
    foundationJsFolder + 'foundation.tabs.js',
    foundationJsFolder + 'foundation.toggler.js',
    foundationJsFolder + 'foundation.tooltip.js',
    foundationJsFolder + 'foundation.util.box.js',
    foundationJsFolder + 'foundation.util.core.js',
    foundationJsFolder + 'foundation.util.imageLoader.js',
    foundationJsFolder + 'foundation.util.keyboard.js',
    foundationJsFolder + 'foundation.util.mediaQuery.js',
    foundationJsFolder + 'foundation.util.motion.js',
    foundationJsFolder + 'foundation.util.nest.js',
    foundationJsFolder + 'foundation.util.timer.js',
    foundationJsFolder + 'foundation.util.touch.js',
    foundationJsFolder + 'foundation.util.triggers.js',
    '/resources/assets/js/foundation-init.js'
], 'public/js/app.js');

mix.browserSync({
    proxy: 'https://dbp.dev'
});