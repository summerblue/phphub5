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
    'resources/assets/js/vendor/upload-image.js',
    'resources/assets/js/vendor/bootstrap-switch.js',
    'resources/assets/js/vendor/messenger.js',
    'resources/assets/js/vendor/anchorific.js',
    'resources/assets/js/vendor/analytics.js',
    'resources/assets/js/vendor/jquery.jscroll.js',
    'resources/assets/js/vendor/jquery.highlight.js',
    'resources/assets/js/vendor/jquery.sticky.js',
    'resources/assets/js/vendor/sweetalert.js',
    'node_modules/social-share.js/dist/js/social-share.min.js',
];

elixir(function(mix) {
    mix
        .copy([
            'node_modules/bootstrap-sass/assets/fonts/bootstrap'
        ], 'public/assets/fonts/bootstrap')

        .copy([
            'node_modules/font-awesome/fonts'
        ], 'public/assets/fonts/font-awesome')

        // https://github.com/overtrue/share.js
        .copy([
            'node_modules/social-share.js/dist/fonts'
        ], 'public/assets/fonts/iconfont')

        .copy([
            'node_modules/social-share.js/dist/fonts'
        ], 'public/build/assets/fonts/iconfont')

        .copy([
            'resources/assets/fonts/googlefont'
        ], 'public/build/assets/fonts/googlefont')

        .sass([
            'base.scss',
            'main.scss',
        ], 'public/assets/css/styles.css')

        .scripts(basejs.concat([
            'resources/assets/js/main.js',
        ]), 'public/assets/js/scripts.js', './')

        // API Web View
        .sass([
            'api/api.scss'
        ], 'public/assets/css/api.css')
        // API Web View
        .scripts([
            'api/emojify.js',
            'api/api.js'
        ], 'public/assets/js/api.js')

        // editor
        .scripts([
            'vendor/inline-attachment.js',
            'vendor/codemirror-4.inline-attachment.js',
            'vendor/simplemde.min.js',
        ], 'public/assets/js/editor.js')

        // API Web View
        .sass([
            'vendor/simplemde.min.scss'
        ], 'public/assets/css/editor.css')

        .version([

            'assets/css/styles.css',
            'assets/js/scripts.js',

            // API Web View
            'assets/css/api.css',
            'assets/js/api.js',

            // API Web View
            'assets/css/editor.css',
            'assets/js/editor.js',

        ]);

    if (production) {
        mix.compress();
    }
});
