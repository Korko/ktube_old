var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

var bowerDir = './resources/assets/vendor/';

var lessPaths = [
    bowerDir + "bootstrap/less"
];

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

    mix
        .less('app.less', 'public/css', { paths: lessPaths })
        .styles(["font.css", bowerDir + "bootstrap/dist/css/bootstrap.css", "main.css"], 'public/css/main.css')
        .styles(["cover.css"], 'public/css/cover.css')
        .styles(["home.css", "loading.css"], 'public/css/app.css')
        .copy(bowerDir + "bootstrap/dist/fonts/glyphicons-halflings-regular.eot", 'public/build/fonts/glyphicons-halflings-regular.eot')
        .copy(bowerDir + "bootstrap/dist/fonts/glyphicons-halflings-regular.svg", 'public/build/fonts/glyphicons-halflings-regular.svg')
        .copy(bowerDir + "bootstrap/dist/fonts/glyphicons-halflings-regular.ttf", 'public/build/fonts/glyphicons-halflings-regular.ttf')
        .copy(bowerDir + "bootstrap/dist/fonts/glyphicons-halflings-regular.woff", 'public/build/fonts/glyphicons-halflings-regular.woff')
        .copy(bowerDir + "bootstrap/dist/fonts/glyphicons-halflings-regular.woff2", 'public/build/fonts/glyphicons-halflings-regular.woff2')
        .scripts([
            'jquery/dist/jquery.min.js',
            'bootstrap/dist/js/bootstrap.min.js',
            'moment/min/moment-with-locales.min.js',
            'vue/dist/vue.min.js',
            'vue-router/dist/vue-router.min.js'
        ], 'public/js/vendor.js', bowerDir)
        .browserify(["script.js", "app.js"], 'public/js/app.js')
        .version(['public/css/main.css', 'public/css/cover.css', 'public/css/app.css', 'public/js/vendor.js', 'public/js/app.js']);

});
