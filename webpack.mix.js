let mix = require('laravel-mix');

// Compile CSS
mix.sass('resources/assets/sass/app.scss', 'public/css');

// Compile JavaScript
mix.js('resources/assets/js/app.js', 'public/js');

// Environment tweaks
if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}
