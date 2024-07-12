const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/bootstrap.scss', 'public/css'); // sesuaikan dengan path bootstrap.scss yang Anda inginkan
 
