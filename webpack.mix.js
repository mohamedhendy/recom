const mix = require("laravel-mix");
const path = require("path");
const tailwindcss = require("tailwindcss");
require('laravel-mix-svg-vue');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.copyDirectory('resources/images', 'public/images');
mix.js("resources/js/app.js", "public/js")
    .svgVue()
    .webpackConfig(require("./webpack.config"));

mix.sass("resources/sass/app.scss", "public/css/app.css")
    .sass("resources/sass/utilities.scss", "public/css/utilities.css")
    .options({
    processCssUrls: false,
    postCss: [tailwindcss("./tailwind.config.js"), require('autoprefixer')]
});

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync({
    // proxy: "http://localhost:8001/",
    proxy: "https://recom.test"
});
