let mix = require('laravel-mix');

// Shorthands for common paths
const path = {
    assets: 'resources/assets/',
    bmd: 'node_modules/bootstrap-material-design/',
    jquery: 'node_modules/jquery/',
    popper: 'node_modules/popper.js/',
}

// Compile app CSS
mix.sass(path.assets + 'sass/app.scss', 'public/css');

// Keep JavaScript vendors in their own separated file
mix.scripts([
    path.jquery + 'dist/jquery.slim.min.js', // Do not use Slim version if you need AJAX features
    path.popper + 'dist/umd/popper.min.js',
    path.bmd + 'dist/js/bootstrap-material-design.min.js',
], 'public/js/vendor.js');

// Compile app JavaScript
mix.js(path.assets + 'js/app.js', 'public/js');

// Environment tweaks
if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}
