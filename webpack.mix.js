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

mix.js(['resources/js/script.js', 'resources/js/product_search.js', 'resources/js/user_search.js'], 'public/js/app.js');

mix.sass('resources/sass/app.scss', 'public/css/app.css');

mix.copyDirectory('resources/images', 'public/images');

let productionSourceMaps = false;
mix.sourceMaps(productionSourceMaps, 'source-map');
