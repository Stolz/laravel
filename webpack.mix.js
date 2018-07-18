let mix = require('laravel-mix');

// Common paths
const path = {
    assets: 'resources/assets/',
    bmd: 'node_modules/bootstrap-material-design/',
    jquery: 'node_modules/jquery/',
    popper: 'node_modules/popper.js/',
}

// Compile SASS into CSS
mix.sass(path.assets + 'sass/app.scss', 'public/css/app.css');

// Combine and minify vanilla JavaScript
mix.scripts([
    path.jquery + 'dist/jquery.slim.min.js', // Do not use Slim version if you need AJAX features
    path.popper + 'dist/umd/popper.min.js',
    path.bmd + 'dist/js/bootstrap-material-design.min.js',
    path.assets + 'js/app.js',
], 'public/js/app.js');


// Compile webpack components
/*mix.js([
    path.assets + 'js/someComponent.js',
], 'public/js/components/someComponent.js');*/

// Add version to assets
if (mix.inProduction()) {
    mix.version();
}
