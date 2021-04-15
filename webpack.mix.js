let mix = require('laravel-mix');
const webpack = require('webpack');

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

mix
.options({ processCssUrls: false })
.sass('resources/assets/sass/app.scss', 'publishable/assets/css')
.sass('resources/assets/sass/front.scss', 'publishable/assets/css')
.copy('node_modules/element-ui/lib/theme-chalk/fonts/element-icons.ttf', 'publishable/assets/fonts/element-icons.ttf')
.copy('node_modules/tinymce/skins', 'publishable/assets/js/skins')
// .copy('node_modules/tinymce/skins', 'publishable/assets/js/skins')
.copy('resources/assets/js/skins', 'publishable/assets/js/skins')
.copy('node_modules/tinymce/themes/modern', 'publishable/assets/js/themes/modern')
.copy('node_modules/ace-builds/src-noconflict', 'publishable/assets/js/ace/libs')
.vue({
    extractStyles: true,
});

if (mix.inProduction()) {
    mix
        .js('resources/assets/js/app.js', 'publishable/assets/js')
}
else{
    mix
        .js('resources/assets/js/app.js', 'publishable/assets/js/app-dev.js')
}

mix.webpackConfig({
    resolve: {
        alias: {
            // ziggy: path.resolve('../../../vendor/tightenco/ziggy/dist'),
        },
    },
    plugins:
    [
        new webpack.NormalModuleReplacementPlugin(/element-ui[\/\\]lib[\/\\]locale[\/\\]lang[\/\\]zh-CN/, 'element-ui/lib/locale/lang/ru-RU')
    ]
});

