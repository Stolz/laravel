let mix = require('laravel-mix');

const path = {
    assets: 'resources/assets/',
    bmd: 'node_modules/bootstrap-material-design/',
    jquery: 'node_modules/jquery/',
    popper: 'node_modules/popper.js/',
    roboto: 'node_modules/typeface-roboto/',
}

mix.copyDirectory(path.roboto + 'files', 'public/css/files');

mix.styles([
    path.roboto + 'index.css',
    path.bmd + 'dist/css/bootstrap-material-design.min.css',
    path.assets + 'css/app.css',
], 'public/css/app.css');

mix.scripts([
    path.jquery + 'dist/jquery.slim.min.js',
    path.popper + 'dist/umd/popper.min.js',
    path.bmd + 'dist/js/bootstrap-material-design.min.js',
    path.assets + 'js/app.js',
], 'public/js/app.js');

if (mix.inProduction()) {
    mix.version();
}
