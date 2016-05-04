var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

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
        .styles(["font.css", "bootstrap.min.css", "main.css"], 'public/css/main.css')
        .styles(["cover.css"], 'public/css/cover.css')
        .styles(["home.css", "loading.css"], 'public/css/app.css')
        .version(['public/css/main.css', 'public/css/cover.css', 'public/css/app.css']);

});
