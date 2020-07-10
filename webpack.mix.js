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

mix.js('resources/js/app.js', 'public/js');

// css1, css2 를 섞어서 public/css/all.css 라는 파일로 생성 
mix.styles([
    'public/css/grid.min.css',
    'public/css/main.css',

], 'public/css/all.css');
