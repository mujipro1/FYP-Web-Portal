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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/components/SuperAdmin.js', 'public/js')
    .js('resources/js/components/expensefarmer.js', 'public/js')
    .react() 
    .extract(["react"])
    .sass('resources/sass/app.scss', 'public/css')
    .postCss('resources/css/app.css', 'public/css')
    .postCss('resources/css/superadmin.css', 'public/css')
    .postCss('resources/css/navBar.css', 'public/css')
    .postCss('resources/css/bootstrap/bootstrap.css', 'public/css')
    .postCss('resources/css/bootstrap/bootstrap.min.css', 'public/css')
    .postCss('resources/css/expensefarmer.css', 'public/css')

