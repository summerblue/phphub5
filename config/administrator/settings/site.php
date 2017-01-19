<?php
/**
 * The main site settings page
 */
return [
	'title' => '站点设置',

	'edit_fields' => array(
		'site_name' => array(
			'title' => '站点名称',
			'type' => 'text',
			'limit' => 50,
		),
		'site_intro_footer' => array(
			'title' => '页脚站点描述',
			'type' => 'textarea',
			'limit' => 250,
		),
		'site_intro_font_page' => array(
			'title' => '首页站点描述',
			'type' => 'textarea',
			'limit' => 250,
		),
		'seo_title' => array(
			'title' => 'SEO - Title',
			'type' => 'textarea',
			'limit' => 250,
		),
		'seo_description' => array(
			'title' => 'SEO - Description',
			'type' => 'textarea',
			'limit' => 250,
		),
		'seo_keyword' => array(
			'title' => 'SEO - Keywords',
			'type' => 'textarea',
			'limit' => 250,
		),
		'compose_topic_hint' => array(
			'title' => '发帖页面用户引导',
			'type' => 'textarea',
			'limit' => 500,
		),
		'logo' => array(
			'title' => '站点 Logo',
			'type' => 'image',
			'naming' => 'random',
			'location' => public_path() . '/assets/images/',
			'size_limit' => 2,
		),
        'logo_width' => array(
			'title' => '站点 Logo 样式',
			'type' => 'textarea',
			'limit' => 500,
		),
	),
	/**
	 * The validation rules for the form, based on the Laravel validation class
	 *
	 * @type array
	 */
	'rules' => array(
		'site_name' => 'required|max:50',
		'logo' => 'required',
	),
	/**
	 * This is run prior to saving the JSON form data
	 *
	 * @type function
	 * @param array		$data
	 *
	 * @return string (on error) / void (otherwise)
	 */
	'before_save' => function(&$data)
	{
		// $data['site_name'] = $data['site_name'] . ' - The Blurst Site Ever';
	},
	/**
	 * The permission option is an authentication check that lets you define a closure that should return true if the current user
	 * is allowed to view this settings page. Any "falsey" response will result in a 404.
	 *
	 * @type closure
	 */
	'permission'=> function()
	{
		return true;
		//return Auth::user()->hasRole('developer');
	},
	/**
	 * This is where you can define the settings page's custom actions
	 */
	'actions' => [
		//Ordering an item up
		'clear_cache' => [
			'title' => '更新系统缓存',
			'messages' => [
				'active' => 'Clearing cache...',
				'success' => 'Cache Cleared',
				'error' => 'There was an error while clearing the page cache',
			],
			//the settings data is passed to the closure and saved if a truthy response is returned
			'action' => function(&$data)
			{
                \Artisan::call('cache:clear');
				return true;
			}
		],
	],
];
