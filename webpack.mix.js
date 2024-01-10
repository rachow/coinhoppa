/**
 * @author: $rachow
 * @copyright: Coinhoppa
 *
 * Asset bundling and purge plus mix it.
 *
*/

const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/assets/dist/js');
mix.js('resources/js/bundle.js', 'public/assets/dist/js');
mix.js('resources/js/bugzy.js', 'public/assets/dist/js');
mix.js('resources/js/ws.js', 'public/assets/dist/js');
mix.postCss('resources/css/app.css', 'public/assets/dist/css', [
    require('tailwindcss'),
]);

//mix.vue();
//mix.sass('resources/sass/app.scss', 'public/assets/dist/css');

mix.options({
  terser: {
    extractComments: false,
  }
});

if (mix.inProduction()) {
	mix.version();
	
	// silence prod notifications.
	mix.disableNotifications();
	// mix.disableSuccessNotifications();
}

