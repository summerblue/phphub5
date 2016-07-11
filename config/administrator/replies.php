<?php

use App\Models\Reply;

return [
    'title'   => '回复',
    'heading' => '回复',
    'single'  => '回复',
    'model'   => Reply::class,

    'columns' => [

        'id' => [
            'title' => 'ID',
        ],
        'user' => [
            'title'    => '用户',
            'sortable' => false,
            'output'   => function ($value, $model) {
                return admin_link(
                    $model->user->name,
                    'users',
                    $model->user_id
                );
            },
        ],
        'topic' => [
            'title'    => '话题',
            'sortable' => false,
            'output'   => function ($value, $model) {
                return admin_link(
                    $model->topic->title,
                    'topics',
                    $model->topic_id
                );
            },
        ],
        'is_blocked' => [
            'title'    => '是否被屏蔽',
        ],
        'vote_count' => [
            'title'    => '投票数量',
        ],
        'operation' => [
            'title'  => '管理',
            'output' => function ($value, $model) {

            },
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
        'user' => [
            'title'              => '用户',
            'type'               => 'relationship',
            'name_field'         => 'name',
            'autocomplete'       => true,
            'search_fields'      => array("CONCAT(id, ' ', name)"),
            'options_sort_field' => 'id',
        ],
        'topic' => [
            'title'              => '话题',
            'type'               => 'relationship',
            'name_field'         => 'title',
            'autocomplete'       => true,
            'search_fields'      => array("CONCAT(id, ' ', title)"),
            'options_sort_field' => 'id',
        ],
        'body_original' => [
            'title'    => 'Markdown 原始内容',
            'hint'     => '请使用 Markdown 格式填写',
            'type'     => 'textarea',
        ],
        'is_blocked' => [
            'title'    => '是否被屏蔽',
            'type'     => 'enum',
            'options'  => [
                'yes' => '是',
                'no'  => '否',
            ],
            'value' => 'no',
        ],
        'vote_count' => [
            'title'    => '投票数量',
        ],
    ],
    'filters' => [
        'user' => [
            'title'              => '用户',
            'type'               => 'relationship',
            'name_field'         => 'name',
            'autocomplete'       => true,
            'search_fields'      => array("CONCAT(id, ' ', name)"),
            'options_sort_field' => 'id',
        ],
        'topic' => [
            'title'              => '话题',
            'type'               => 'relationship',
            'name_field'         => 'title',
            'autocomplete'       => true,
            'search_fields'      => array("CONCAT(id, ' ', title)"),
            'options_sort_field' => 'id',
        ],
        'is_blocked' => [
            'title'    => '是否被屏蔽',
            'type'     => 'enum',
            'options'  => [
                'yes' => '是',
                'no'  => '否',
            ],
        ],
        'body_original' => [
            'title'    => '回复内容',
        ],
        'vote_count' => [
            'type'                => 'number',
            'title'               => '查看次数',
            'thousands_separator' => ',', //optional, defaults to ','
            'decimal_separator'   => '.',   //optional, defaults to '.'
        ],
    ],
    'rules'   => [
        'body_original' => 'required'
    ],
    'messages' => [
        'body_original.required' => '请填写回复内容',
    ],
];
