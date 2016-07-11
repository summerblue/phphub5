<?php

use App\Models\User;

return [
    'title'   => '用户',
    'heading' => '用户',
    'single'  => '用户',
    'model'   => User::class,

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'image_url' => [
            'title'  => '头像',
            'output' => function ($value) {
                return empty($value) ? 'N/A' : <<<EOD
    <img src="$value" width="80">
EOD;
            },
            'sortable' => false,
        ],
        'name' => [
            'title'    => '用户名',
            'sortable' => false,
        ],
        'real_name' => [
            'title'    => '真实姓名',
            'sortable' => false,
        ],
        'github_name' => [
            'title' => 'Github 用户名'
        ],
        'email' => [
            'title' => '邮箱',
        ],
        'github_id' => [
            'title' => 'Github Id',
        ],
        'is_banned' => [
            'title' => '是否被屏蔽',
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
            'title' => '姓名',
        ],
        'email' => [
            'title' => '邮箱',
        ],
        'password' => [
            'title' => '密码',
        ],
        'github_id' => [
            'title' => 'Github ID'
        ],
        'github_url' => [
            'title' => 'Github URL'
        ],
        'is_banned' => [
            'title'    => '是否被屏蔽',
            'type'     => 'enum',
            'options'  => [
                'yes' => '是',
                'no'  => '否',
            ],
        ],
        'image_url' => [
            'title' => 'Github 头像 URL'
        ],
        'city' => [
            'title' => '所处城市'
        ],
        'company' => [
            'title' => '所处公司'
        ],
        'twitter_account' => [
            'title' => 'Twitter 账号'
        ],
        'personal_website' => [
            'title' => '个人网站'
        ],
        'introduction' => [
            'title' => '个人简介'
        ],
        'github_name' => [
            'title' => 'Github 用户名'
        ],
        'real_name' => [
            'title' => '真是姓名'
        ],
        'avatar' => [
            'title' => '头像 URL'
        ],
        'roles' => array(
            'type'       => 'relationship',
            'title'      => '用户组',
            'name_field' => 'display_name',
        ),
    ],
    'filters' => [
        'id' => [
            'title' => '用户 ID',
        ],
        'name' => [
            'title' => '姓名',
        ],
        'github_name' => [
            'title' => 'Github 用户名'
        ],
        'real_name' => [
            'title' => '真是姓名'
        ],
        'email' => [
            'title' => '邮箱',
        ],
        'roles' => [
            'type'       => 'relationship',
            'title'      => '用户组',
            'name_field' => 'display_name',
        ],
        'is_banned' => [
            'title'    => '是否被屏蔽',
            'type'     => 'enum',
            'options'  => [
                'yes' => '是',
                'no'  => '否',
            ],
        ],
        'city' => [
            'title' => '所处城市'
        ],
        'company' => [
            'title' => '所处公司'
        ],
        'twitter_account' => [
            'title' => 'Twitter 账号'
        ],
        'personal_website' => [
            'title' => '个人网站'
        ],
        'introduction' => [
            'title' => '个人简介'
        ],
    ],
    'actions' => [],
];
