<?php

use App\Models\Tag;

return [
    'title'   => '标签',
    'heading' => '标签',
    'single'  => '标签',
    'model'   => Tag::class,

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title'    => '名称',
            'sortable' => false,
        ],
        'slug' => [
            'title'    => 'Slug',
            'sortable' => false,
        ],
        'description' => [
            'title'    => '描述',
            'sortable' => false,
        ],
        'depth' => [
            'title'    => '标签层次（0 最大）',
            'sortable' => false,
        ],
        'count' => [
            'title'    => '打过标签的内容数量',
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
        'name' => [
            'title' => '名称',
        ],
        'slug' => [
            'title' => 'Slug',
        ],
        'description' => [
            'title' => '描述',
        ],
    ],
    'filters' => [
        'id' => [
            'title' => '标签 ID',
        ],
        'name' => [
            'title' => '名称',
        ],
    ],
    'actions' => [],
    'rules'   => [
        'name' => 'required|min:1|unique:tags'
    ],
    'messages' => [
        'name.unique'   => '标签名在数据库里有重复，请选用其他名称。',
        'name.required' => '请确保名字至少一个字符以上',
    ],
];
