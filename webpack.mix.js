const mix = require('laravel-mix');

mix.postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]).options({
    processCssUrls: false,
});

mix.postCss('resources/css/admin-tw.css', 'public/css', [
    require('tailwindcss')('./tailwind.admin.config.js'),
    require('autoprefixer'),
]);
