<?php

use App\Models\Tip;

return [
    'title'   => '小窍门',
    'heading' => '小窍门',
    'single'  => '小窍门',
    'model'   => Tip::class,

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'body' => [
            'title'    => '内容',
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
        'body' => [
            'title' => '内容',
            'type'  => 'textarea',
        ],
    ],
    'filters' => [
        'id' => [
            'title' => '标签 ID',
        ],
        'body' => [
            'title' => '内容',
        ],
    ],
];
