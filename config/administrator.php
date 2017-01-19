<?php

return array(

    /*
     * Package URI
     *
     * @type string
     */
    'uri' => 'admin',

    /*
     *  Domain for routing.
     *
     *  @type string
     */
    'domain' => '',

    /*
     * Page title
     *
     * @type string
     */
    'title' => 'PHPHub 管理后台',

    /*
     * The path to your model config directory
     *
     * @type string
     */
    'model_config_path' => config_path('administrator'),

    /*
     * The path to your settings config directory
     *
     * @type string
     */
    'settings_config_path' => config_path('administrator/settings'),

    /*
     * The menu structure of the site. For models, you should either supply the name of a model config file or an array of names of model config
     * files. The same applies to settings config files, except you must prepend 'settings.' to the settings config file name. You can also add
     * custom pages by prepending a view path with 'page.'. By providing an array of names, you can group certain models or settings pages
     * together. Each name needs to either have a config file in your model config path, settings config path with the same name, or a path to a
     * fully-qualified Laravel view. So 'users' would require a 'users.php' file in your model config path, 'settings.site' would require a
     * 'site.php' file in your settings config path, and 'page.foo.test' would require a 'test.php' or 'test.blade.php' file in a 'foo' directory
     * inside your view directory.
     *
     * @type array
     *
     * 	array(
     *		'E-Commerce' => array('collections', 'products', 'product_images', 'orders'),
     *		'homepage_sliders',
     *		'users',
     *		'roles',
     *		'colors',
     *		'Settings' => array('settings.site', 'settings.ecommerce', 'settings.social'),
     * 		'Analytics' => array('E-Commerce' => 'page.ecommerce.analytics'),
     *	)
     */
    'menu' => [
        '用户管理' => [
            'users',
            'roles',
            'permissions',
        ],
        '内容管理' => [
            'topics',
            'replies',
            'categories',
            'tags'
        ],
        '站点管理' => [
            'settings.site',
            'banners',
            'links',
            'sites',
            'site_statuses',
            'revisions',
        ],
    ],

    /*
     * The permission option is the highest-level authentication check that lets you define a closure that should return true if the current user
     * is allowed to view the admin section. Any "falsey" response will send the user back to the 'login_path' defined below.
     *
     * @type closure
     */
    'permission' => function () {
        if (App::environment('local')) {
            if (!Auth::check()) {
                $user = App\Models\User::first();
                $user && Auth::login($user);
            } else {
                return true;
            }
        }

        if (!Auth::check() || !Auth::user()->can('visit_admin') || Auth::user()->roles->count() > 5) {
            return false;
        }

        return true;
    },

    /*
     * This determines if you will have a dashboard (whose view you provide in the dashboard_view option) or a non-dashboard home
     * page (whose menu item you provide in the home_page option)
     *
     * @type bool
     */
    'use_dashboard' => false,

    /*
     * If you want to create a dashboard view, provide the view string here.
     *
     * @type string
     */
    'dashboard_view' => '',

    /*
     * The menu item that should be used as the default landing page of the administrative section
     *
     * @type string
     */
    'home_page' => 'site_statuses',

    /*
     * The route to which the user will be taken when they click the "back to site" button
     *
     * @type string
     */
    'back_to_site_path' => '/',

    /*
     * The login path is the path where Administrator will send the user if they fail a permission check
     *
     * @type string
     */
    'login_path' => '/login',

    /*
     * The logout path is the path where Administrator will send the user when they click the logout link
     *
     * @type string
     */
    'logout_path' => false,

    /*
     * This is the key of the return path that is sent with the redirection to your login_action. Session::get('redirect') will hold the return URL.
     *
     * @type string
     */
    'login_redirect_key' => 'redirect',

    /*
     * Global default rows per page
     *
     * @type int
     */
    'global_rows_per_page' => 20,

    /*
     * An array of available locale strings. This determines which locales are available in the languages menu at the top right of the Administrator
     * interface.
     *
     * @type array
     */
    'locales' => array(),

    'custom_routes_file' => app_path('Http/routes/administrator.php'),
);
