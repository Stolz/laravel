// Configure Laravel Mix
let mix = require('laravel-mix');

mix.setPublicPath('public');

if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}

mix.webpackConfig({
    resolve: {
        alias: {
            'circle-progress': 'jquery-circle-progress',
            'core': path.resolve(__dirname, 'node_modules/tabler-ui/dist/assets/js/core.js'),
            //'vector-map': 'jvectormap'
        }
    }
});

mix.autoload({
    jquery: ['$', 'jQuery', 'jquery', 'window.jQuery'],
});

// Compile CSS
mix.sass('resources/assets/sass/app.scss', 'public/css');

// Compile JavaScript
mix.js('resources/assets/js/app.js', 'public/js');

// Keep JavaScript vendors in their own separated file
mix.extract([
    'bootstrap',
    //'bootstrap-datepicker',
    //'bootstrap-sass',
    //'chart.js',
    //'d3',
    'jquery',
    //'jquery-circle-progress',
    //'jvectormap',
    //'lodash',
    //'moment',
    'popper.js',
    //'requirejs/require',
    //'select2',
    'selectize',
    //'sparkline',
], 'public/js/vendor.js');

// Compile charts
/* Remember to also load charts-c3/plugin in app.scss file
mix.scripts([
    'node_modules/tabler-ui/dist/assets/plugins/charts-c3/js/d3.v3.min.js',
    'node_modules/tabler-ui/dist/assets/plugins/charts-c3/js/c3.min.js'
], 'public/js/charts.js');*/
