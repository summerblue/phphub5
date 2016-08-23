<?php

use App\Models\Site;

return [
    'title'   => '酷站',
    'heading' => '酷站',
    'single'  => '酷站',
    'model'   => Site::class,

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'favicon' => [
            'title'    => '图片',
            'output'   => function ($value, $model) {
                $value = $model->present()->icon();
                return empty($value) ? 'N/A' : <<<EOD
    <img src="$value" width="16">
EOD;
            },
            'sortable' => false,
        ],
        'title' => [
            'title'    => '名称',
            'sortable' => false,
        ],
        'description' => [
            'title'    => '描述',
            'sortable' => false,
            'output'   => function ($value) {
                return '<p style="width:200px">'.$value.'</p>';
            },
        ],
        'link' => [
            'title'    => '链接',
            'sortable' => false,
            'output'   => function ($value) {
                return '<p style="width:280px">'.$value.'</p>';
            },
        ],
        'operation' => [
            'title'  => '管理',
            'output' => function ($value, $model) {
                return $value;
            },
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
        'title' => [
            'title'    => '名称',
        ],
        'description' => [
            'title'    => '描述',
            'type'     => 'textarea',
        ],
        'link' => [
            'title'    => '链接',
        ],
        'favicon' => [
            'title'    => '图片',
            'type' => 'file',
            'location' => public_path() . '/uploads/sites/',
            'mimes' => 'jpeg,bmp,png,gif,ico',
        ],
        'order' => [
            'title'    => '排序',
            'hint'    => '数值越高，越排在前面，默认为 0',
            'value'    => 0,
        ],
        'type' => [
            'title'    => '类型',
            'type'     => 'enum',
            'options'  => [
                'site' => '网站',
                'blog' => '博客',
                'weibo' => '微博',
                'dev_service'  => '开发者服务',
                'site_foreign'  => '国外网站',
            ],
            'value' => 'site',
        ],
    ],
    'filters' => [
        'id' => [
            'title' => 'ID',
        ],
        'title' => [
            'title' => '名称',
        ],
        'link' => [
            'title' => '链接',
        ],
        'type' => [
            'title'    => '类型',
            'type'     => 'enum',
            'options'  => [
                'site' => '网站',
                'blog' => '博客',
                'site_foreign' => '国外 Laravel 网站',
                'dev_service'  => '开发者服务',
            ],
        ],
    ],
];
