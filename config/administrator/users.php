<?php

use App\Models\User;

return [
    'title'   => '用户',
    'heading' => '用户',
    'single'  => '用户',
    'model'   => User::class,

    'permission'=> function()
    {
        return Auth::user()->may('manage_users');
    },

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'image_url' => [
            'title'  => '头像',
            'output' => function ($value, $model) {
                $value = $model->present()->gravatar();
                return empty($value) ? 'N/A' : '<img src="'.$value.'" width="80">';
            },
            'sortable' => false,
        ],
        'name' => [
            'title'    => '用户名',
            'sortable' => false,
            'output' => function ($value, $model) {
                return '<a href="/users/'.$model->id.'" target=_blank>'.$value.'</a>';
            },
        ],
        'real_name' => [
            'title'    => '真实姓名',
            'sortable' => false,
        ],
        'github_name' => [
            'title' => 'GitHub 用户名'
        ],
        'topic_count' => [
            'title' => '话题数量'
        ],
        'reply_count' => [
            'title' => '回复数量'
        ],
        'register_source' => [
            'title'  => '注册来源',
        ],
        'email' => [
            'title' => '邮箱',
        ],
        'is_banned' => [
            'title'  => '是否被屏蔽',
            'output' => function ($value) {
                return admin_enum_style_output($value, true);
            },
        ],
        'verified' => [
            'title'  => '邮箱是否已验证',
            'output' => function ($value) {
                $value = $value ? 'yes' : 'no';
                return admin_enum_style_output($value);
            },
        ],
        'email_notify_enabled' => [
            'title'  => '是否开启邮件提醒',
            'output' => function ($value) {
                return admin_enum_style_output($value);
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
        'name' => [
            'title' => '姓名',
        ],
        'email' => [
            'title' => '邮箱',
        ],
        'github_id' => [
            'title' => 'GitHub ID'
        ],
        'github_url' => [
            'title' => 'GitHub URL'
        ],
        'wechat_openid' => [
            'title' => '微信 openid',
        ],
        'wechat_unionid' => [
            'title' => '微信 unionid',
        ],
        'register_source' => [
            'title'  => '注册来源',
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
        'certification' => [
            'title' => '认证信息',
            'type' => 'textarea',
        ],
        'github_name' => [
            'title' => 'GitHub 用户名'
        ],
        'real_name' => [
            'title' => '真实姓名'
        ],
        'avatar' => [
            'title' => '用户头像',
            'type' => 'image',
            'location' => public_path() . '/uploads/avatars/',
        ],
        'roles' => array(
            'type'       => 'relationship',
            'title'      => '用户组',
            'name_field' => 'display_name',
        ),
        'register_source' => [
            'title'  => '注册来源',
        ],
    ],
    'filters' => [
        'id' => [
            'title' => '用户 ID',
        ],
        'name' => [
            'title' => '姓名',
        ],
        'github_name' => [
            'title' => 'GitHub 用户名'
        ],
        'real_name' => [
            'title' => '真实姓名'
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
        'email_notify_enabled' => [
            'title'    => '是否开启邮件提醒',
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
        'register_source' => [
            'title'  => '注册来源',
        ],
    ],
    'actions' => [
        'banned_user' => [
            'title'    => '禁用',
            'messages' => array(
                'active'  => '正在处理...',
                'success' => '处理成功',
                'error'   => '处理失败，请重新尝试',
            ),
            'permission' => function ($model) {
                return $model->is_banned == 'no';
            },
            'action' => function ($model) {
                $model->is_banned = 'yes';
                $model->save();
                return true;
            }
        ],
        'unbanned_user' => [
            'title'    => '启用',
            'messages' => array(
                'active'  => '正在处理...',
                'success' => '处理成功',
                'error'   => '处理失败，请重新尝试',
            ),
            'permission' => function ($model) {
                return $model->is_banned == 'yes';
            },
            'action' => function ($model) {
                $model->is_banned = 'no';
                $model->save();
                return true;
            }
        ],
        'verified_email' => [
            'title'    => '设置邮箱为已激活',
            'messages' => array(
                'active'  => '正在处理...',
                'success' => '处理成功',
                'error'   => '处理失败，请重新尝试',
            ),
            'permission' => function ($model) {
                return !$model->verified;
            },
            'action' => function ($model) {
                $model->verified = true;
                $model->save();
                return true;
            }
        ],
        'unverified_email' => [
            'title'    => '设置邮箱为未激活',
            'messages' => array(
                'active'  => '正在处理...',
                'success' => '处理成功',
                'error'   => '处理失败，请重新尝试',
            ),
            'permission' => function ($model) {
                return $model->verified;
            },
            'action' => function ($model) {
                $model->verified = false;
                $model->save();
                return true;
            }
        ],
        'email_notify_enabled' => [
            'title'    => ' 开启邮件提醒',
            'messages' => array(
                'active'  => '正在处理...',
                'success' => '处理成功',
                'error'   => '处理失败，请重新尝试',
            ),
            'permission' => function ($model) {
                return $model->email_notify_enabled == 'no';
            },
            'action' => function ($model) {
                $model->email_notify_enabled = 'yes';
                $model->save();
                return true;
            }
        ],
        'email_notify_disenabled' => [
            'title'    => '关闭邮件提醒',
            'messages' => array(
                'active'  => '正在处理...',
                'success' => '处理成功',
                'error'   => '处理失败，请重新尝试',
            ),
            'permission' => function ($model) {
                return $model->email_notify_enabled == 'yes';
            },
            'action' => function ($model) {
                $model->email_notify_enabled = 'no';
                $model->save();
                return true;
            }
        ],
    ],
];
