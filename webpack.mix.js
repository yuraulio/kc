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
mix
  .vue({ version: 2 })
  .options({
    processCssUrls: false,
  })
  .js('resources/js/app.js', 'public/js')
  .js('resources/js/panel_app/app.js', 'public/js/panel_app.js')
  .js('resources/js/new_cart_app.js', 'public/js')
  .sass('resources/assets/scss/argon.scss', 'public/css')
  .sass('resources/scss/panel_app.scss', 'public/css')
  .sass('resources/scss/new_cart.scss', 'public/new_cart/css')
  .sass('resources/assets/scss/editor.scss', 'public/theme/assets/css/editor.css');


/* Frontend Theme css */
/*mix.styles([
          '../../../public/theme/assets/css/style.css',
      ], 'public/theme/assets/css/style_ver.css');*/

mix.styles(
  [
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
  ],
  'public/theme/assets/css/style_ver.css'
);

mix.styles(
  [
    'public/theme/assets/css/fonts.css',
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
    'public/theme/assets/css/editor.css',
    // 'public/admin_assets/css/icons.css',
  ],
  'public/theme/assets/css/style_ver_new.css'
);

mix.sass('resources/assets/scss/bootstrap5/bootstrap5-grid.scss', 'public/theme/assets/css');

mix.sass('resources/assets/scss/bootstrap5/bootstrap.scss', 'public/theme/assets/css');

mix.styles(
  [
    'public/argon/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
    'public/argon/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css',
    'public/argon/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css',
  ],
  'public/css/argon_vendors.css'
);

mix.js('resources/js/blog.js', 'public/js');


mix.scripts(
  [
    'public/theme/assets/js/new_js/vendor/modernizr-3.7.1.min.js',
    'public/theme/assets/js/new_js/jquery-3.4.1.min.js',
    'public/theme/assets/js/new_js/jquery-ui.js',
    'public/theme/assets/js/new_js/plugins.js',
    'public/theme/assets/js/new_js/main.js',
    'public/theme/assets/js/new_js/select2.js',
    'public/theme/assets/js/marquee3k.js',
  ],
  'public/theme/assets/js/front.js'
);

mix.scripts(
  [
    'node_modules/daterangepicker/daterangepicker.js',
    'public/argon/vendor/datatables.net/js/jquery.dataTables.min.js',
    'public/argon/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
    'public/argon/vendor/datatables.net-buttons/js/dataTables.buttons.min.js',
    'public/argon/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js',
    'public/argon/vendor/datatables.net-buttons/js/buttons.html5.min.js',
    'public/argon/vendor/datatables.net-buttons/js/buttons.flash.min.js',
    'public/argon/vendor/datatables.net-buttons/js/buttons.print.min.js',
    'public/argon/vendor/datatables.net-select/js/dataTables.select.min.js',
  ],
  'public/js/argon_vendors.js'
);

mix.version([
  'public/theme/assets/css/style_ver.css',
  'public/theme/assets/js/front.js',
  'public/theme/assets/css/new/pop_up.css',
  'public/theme/assets/css/new/burger.css',
  'public/theme/assets/css/new/normalize.css',
  'public/theme/assets/css/new/core.css',
  'public/theme/assets/js/new_js1/app1.js',
  'public/theme/assets/js/new_js1/burger.js',
  'public/theme/assets/js/marquee3k.js',
]);

// MediaManager
mix
  .sass('resources/assets/vendor/MediaManager/sass/manager.scss', 'public/assets/vendor/MediaManager/style.css')
  .copyDirectory('resources/assets/vendor/MediaManager/dist', 'public/assets/vendor/MediaManager');

mix.webpackConfig({
  watchOptions: {
    ignored: /node_modules/,
    aggregateTimeout: 200,
    poll: 1000,
  },
});
