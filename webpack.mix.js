const mix = require('laravel-mix');
require('laravel-mix-bundle-analyzer');

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
mix.disableNotifications();

if (!mix.inProduction()) {
    // mix.bundleAnalyzer();
}

mix.webpackConfig({
    output: {
        chunkFilename: 'js/chunks/[name].js',
    },
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js/App"),
            "#": path.resolve(__dirname, "resources/sass/App")
        }
    }
});

/**
 * App
 */
mix.js('resources/js/App/app.js', 'public/js')
    .sass('resources/sass/App/app.scss', 'public/css')
    .sass('resources/sass/App/embed/post.scss', 'public/css/embed')
    .sass('resources/sass/App/auth/reset.scss', 'public/css/auth').version();


/**
 * Dashboard
 */
// mix.sass('resources/sass/Dashboard/app.scss', 'public/assets/dashboard/css')
//     .js('resources/js/Dashboard/app.js', 'public/assets/dashboard/js').extract();