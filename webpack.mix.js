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
    .vue({ version: 2 })
   .sass('resources/assets/scss/argon.scss', 'public/css');

   //elixir(function(mix) {

        /* Frontend Theme css */
      /*mix.styles([
          '../../../public/theme/assets/css/style.css',
      ], 'public/theme/assets/css/style_ver.css');*/

      mix.styles([
          'public/theme/assets/css/old.css',
          'public/theme/assets/css/normalize.css',
          'public/theme/assets/css/jquery.mCustomScrollbar.css',
          'public/theme/assets/css/jquery-ui.css',
          'public/theme/assets/css/grid.css',
          'public/theme/assets/css/grid-flex.css',
          'public/theme/assets/css/global.css',
          'public/theme/assets/css/main.css',
          'public/theme/assets/css/fontawesome/css/kcfonts.css',
          'public/theme/assets/css/select2.css',
          'public/theme/assets/css/my_datepicker.css',
          'public/theme/assets/css/carouselTicker.css'
      ], 'public/theme/assets/css/style_ver.css');

      mix.styles([
        'public/theme/assets/css/old.css',
        'public/theme/assets/css/normalize.css',
        'public/theme/assets/css/jquery.mCustomScrollbar.css',
        'public/theme/assets/css/jquery-ui.css',
        'public/theme/assets/css/grid.css',
        'public/theme/assets/css/grid-flex.css',
        'public/theme/assets/css/global.css',
        'public/theme/assets/css/main.css',
        'public/theme/assets/css/fontawesome/css/kcfonts.css',
        'public/theme/assets/css/select2.css',
        'resources/assets/css/custom.css',
        'resources/assets/css/editor.css',
        'public/theme/assets/css/carouselTicker.css'
        // 'public/admin_assets/css/icons.css',
    ], 'public/theme/assets/css/style_ver_new.css');

    mix.styles([
        'resources/assets/css/editor.css',
    ], 'public/theme/assets/css/editor.css');

    mix.sass('resources/assets/scss/bootstrap5/bootstrap5-grid.scss',
        'public/theme/assets/css'
    );

    mix.sass('resources/assets/scss/bootstrap5/bootstrap.scss',
        'public/theme/assets/css'
    );

    mix.js('resources/js/blog.js', 'public/js')

      mix.styles([
        'public/new_cart/css/style.css',
    ], 'public/new_cart/version/style_ver.css');

      /* **/

      mix.scripts([

          'public/theme/assets/js/new_js/vendor/modernizr-3.7.1.min.js',
          'public/theme/assets/js/new_js/jquery-3.4.1.min.js',
          'public/theme/assets/js/new_js/jquery-ui.js',
          'public/theme/assets/js/new_js/plugins.js',
          'public/theme/assets/js/jquery.carouselTicker.js',
          'public/theme/assets/js/new_js/main.js',
          'public/theme/assets/js/new_js/select2.js',
          
      ],'public/theme/assets/js/front.js')

      mix.version([
          'public/theme/assets/css/style_ver.css',
          'public/theme/assets/js/front.js',
          'public/new_cart/version/style_ver.css',
          'public/theme/assets/css/new/pop_up.css',
          'public/theme/assets/css/new/burger.css',
          'public/theme/assets/css/new/normalize.css',
          'public/theme/assets/css/new/core.css',
          'public/theme/assets/js/new_js1/app1.js',
          'public/theme/assets/js/new_js1/burger.js',
          'public/theme/assets/js/jquery.carouselTicker.js',

      ]);
  //});

// MediaManager
mix.sass('resources/assets/vendor/MediaManager/sass/manager.scss', 'public/assets/vendor/MediaManager/style.css')
    .copyDirectory('resources/assets/vendor/MediaManager/dist', 'public/assets/vendor/MediaManager')

mix.webpackConfig({
    watchOptions: {
        ignored: /node_modules/,
        aggregateTimeout: 200,
        poll: 1000,
    }
});
