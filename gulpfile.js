var elixir = require('laravel-elixir');
require('laravel-elixir-livereload');
require('laravel-elixir-compress');

var production = elixir.config.production;
var basejs = [
    'vendor/jquery.min.js',
    'vendor/bootstrap.min.js',
    'vendor/moment.min.js',
    'vendor/zh-cn.min.js',
    'vendor/emojify.min.js',
    'vendor/jquery.scrollUp.js',
    'vendor/jquery.pjax.js',
    'vendor/nprogress.js',
    'vendor/jquery.autosize.min.js',
    'vendor/prism.js',
    'vendor/jquery.textcomplete.js',
    'vendor/emoji.js',
    'vendor/marked.min.js',
    'vendor/ekko-lightbox.js',
    'vendor/localforage.min.js',
    'vendor/jquery.inline-attach.min.js',
    'vendor/snowfall.jquery.min.js',
];

elixir(function(mix) {
    mix
        .copy([
            'node_modules/bootstrap-sass/assets/fonts/bootstrap'
        ], 'public/assets/fonts/bootstrap')

        .copy([
            'node_modules/font-awesome/fonts'
        ], 'public/assets/fonts/font-awesome')

        .sass([
            'base.scss',
            'main.scss',
        ], 'public/assets/css/styles.css')

        .scripts(basejs.concat([
            'main.js',
        ]), 'public/assets/js/scripts.js')

        .version([
            'assets/css/styles.css',
            'assets/js/scripts.js',
        ])

        .livereload();

    if (production) {
        mix.compress();
    }
});
