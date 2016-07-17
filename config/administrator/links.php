<?php

use App\Models\Link;

return [
    'title'   => '友情链接',
    'heading' => '友情链接',
    'single'  => '友情链接',
    'model'   => Link::class,

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'title' => [
            'title'    => '名称',
            'sortable' => false,
        ],
        'link' => [
            'title'    => '链接',
            'sortable' => false,
        ],
        'cover' => [
            'title'    => '图片',
            'output' => function ($value) {
                return empty($value) ? 'N/A' : <<<EOD
    <img src="$value" width="180">
EOD;
            },
            'sortable' => false,
        ],
        'is_enabled' => [
            'title'    => '是否启用',
            'output' => function ($value) {
                return adminEnumStyleOutput($value);
            },
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
        'title' => [
            'title'    => '名称',
        ],
        'link' => [
            'title'    => '链接',
        ],
        'cover' => [
            'title'    => '图片',
        ],
    ],
    'filters' => [
        'id' => [
            'title' => '标签 ID',
        ],
        'title' => [
            'title' => '名称',
        ],
    ],
];
