const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')

    ;


mix.webpackConfig({
    output: {
        chunkFilename: 'js/chunks/[name].js',
    }
});

