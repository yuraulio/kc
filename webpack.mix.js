const mix = require('laravel-mix');
//var elixir = require('laravel-elixir');
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
   .sass('resources/assets/scss/argon.scss', 'public/css');


   //elixir(function(mix) {
   
        /* Frontend Theme css */
      /*mix.styles([
          '../../../public/theme/assets/css/style.css',
      ], 'public/theme/assets/css/style_ver.css');*/
  
      mix.styles([
          '../../../public/theme/assets/css/old.css', 
          '../../../public/theme/assets/css/normalize.css', 
          '../../../public/theme/assets/css/jquery.mCustomScrollbar.css',
          '../../../public/theme/assets/css/jquery-ui.css', 
          '../../../public/theme/assets/css/grid.css', 
          '../../../public/theme/assets/css/grid-flex.css', 
          '../../../public/theme/assets/css/global.css', 
          '../../../public/theme/assets/css/main.css', 
          '../../../public/theme/assets/css/fontawesome/css/kcfonts.css',
          '../../../public/theme/assets/css/select2.css', 
      ], 'public/theme/assets/css/style_ver.css');
  
      /* */
  
      mix.scripts([
          
          '../../../public/theme/assets/js/new_js/vendor/modernizr-3.7.1.min.js',
          '../../../public/theme/assets/js/new_js/jquery-3.4.1.min.js',
          '../../../public/theme/assets/js/new_js/jquery-ui.js',
          '../../../public/theme/assets/js/new_js/plugins.js',
          '../../../public/theme/assets/js/new_js/main.js',
          '../../../public/theme/assets/js/new_js/select2.js',
      ],'public/theme/assets/js/front.js')
  
      mix.version([
          'public/theme/assets/css/style_ver.css',
          'public/theme/assets/js/front.js',
      ]);
  //});
