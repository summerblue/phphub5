var elixir = require('laravel-elixir');
require('laravel-elixir-livereload');
require('laravel-elixir-compress');

var production = elixir.config.production;
var basejs = [
    'resources/assets/js/vendor/jquery.min.js',
    'resources/assets/js/vendor/bootstrap.min.js',
    'resources/assets/js/vendor/moment.min.js',
    'resources/assets/js/vendor/zh-cn.min.js',
    'resources/assets/js/vendor/emojify.min.js',
    'resources/assets/js/vendor/jquery.scrollUp.js',
    'resources/assets/js/vendor/jquery.pjax.js',
    'resources/assets/js/vendor/nprogress.js',
    'resources/assets/js/vendor/jquery.autosize.min.js',
    'resources/assets/js/vendor/prism.js',
    'resources/assets/js/vendor/jquery.textcomplete.js',
    'resources/assets/js/vendor/emoji.js',
    'resources/assets/js/vendor/marked.min.js',
    'resources/assets/js/vendor/ekko-lightbox.js',
    'resources/assets/js/vendor/localforage.min.js',
    'resources/assets/js/vendor/jquery.inline-attach.min.js',
    'resources/assets/js/vendor/snowfall.jquery.min.js',
    'node_modules/sweetalert/dist/sweetalert.min.js',
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
            'resources/assets/js/main.js',
        ]), 'public/assets/js/scripts.js', './')

        .version([
            'assets/css/styles.css',
            'assets/js/scripts.js',
        ])

        .livereload();

    if (production) {
        mix.compress();
    }
});
