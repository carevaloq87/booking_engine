let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .js('resources/assets/js/booking_engine.js', 'public/js')
   .js('resources/assets/js/booking_engine_resource.js', 'public/js')
   .js('resources/assets/js/service.js','public/js')
   .js('resources/assets/js/resource.js','public/js')
   .js('resources/assets/js/booking.js','public/js')
   .js('resources/assets/js/new_user.js','public/js');
