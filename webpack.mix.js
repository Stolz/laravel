let mix = require('laravel-mix');

mix.styles(['resources/assets/css/app.css'], 'public/css/app.css');
mix.scripts(['resources/assets/js/app.js' ], 'public/js/app.js');

if (mix.inProduction()) {
    mix.version();
}
