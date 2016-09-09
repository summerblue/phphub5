<?php

use App\Models\Banner;

return [
    'title'   => 'Banner',
    'heading' => 'Banner',
    'single'  => 'Banner',
    'model'   => Banner::class,

    'query_filter' => function ($query) {
        if (!Input::get('sortOptions')) {
            $query->orderBy('order', 'ASC');
        }
    },

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'position' => [
            'title' => '位置',
        ],
        'title' => [
            'title'  => '标题',
            'output' => function ($value, $model) {
                return $model->link ? "<a href='{$model->link}' target='_blank'>{$value}</a>" : $value;
            },
        ],
        'target' => [
            'title'  => '打开方式',
            'output' => function ($value) {
                return $value == '_blank' ? '新窗口打开' : '本站打开';
            },
        ],
        'image_url' => [
            'title'    => '图片',
            'sortable' => false,
            'output'   => function ($value, $model) {
                return $value ? "<img src='$value' width='200' height='100'>" : 'N/A';
            },
        ],
        'description' => [
            'title'    => '描述',
            'sortable' => false,
            'output'   => function ($value, $model) {
                return $value ? "<p style='width:250px'>$value</p>" : 'N/A';
            },
        ],
        'order' => [
            'title'    => '排序（按小到大排序）',
            'sortable' => false,
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
        'position' => [
            'title' => '位置',
            'type'     => 'enum',
            'options'  => [
                'website_top' => '友站',
                'footer-sponsor'  => '页脚赞助商',
                'sidebar-sponsor'  => '右边栏赞助商',
                'sidebar-resources'  => '右边栏资源推荐',
            ],
        ],
        'title' => [
            'title' => '标题',
        ],
        'target' => [
            'title'    => '打开方式',
            'type'     => 'enum',
            'options'  => [
                '_blank' => '新窗口打开',
                '_self'  => '本站打开',
            ],
            'value' => '_blank',
        ],
        'link' => [
            'title' => '链接地址',
        ],
        'image_url' => [
            'title'             => '封面',
            'type'              => 'image',
            'location'          => public_path() . '/uploads/banners/',
            'naming'            => 'random',
            'length'            => 20,
            'size_limit'        => 2,
            'display_raw_value' => false,
        ],
        'description' => [
            'title' => '描述',
            'type'  => 'textarea',
        ],
        'order' => [
            'title' => '排序（按小到大排序）',
            'type'  => 'number',
            'value' => 0,
        ],
    ],
    'filters' => [
        'id' => [
            'title' => 'ID',
        ],
        'position' => [
            'title' => '位置',
        ],
        'title' => [
            'title' => '标题',
        ],
        'order' => [
            'title' => '排序（按小到大排序）',
            'type'  => 'number',
        ],
    ],
];
