<?php
use App\Models\Permission;

return [
    'title'   => '用户组权限',
    'heading' => '用户组权限',
    'single'  => '用户组权限',
    'model'   => Permission::class,

    'permission' => function () {
        return Auth::user()->may('manage_users');
    },

    'action_permissions' => [
        'create' => function ($model) {
            return true;
        },
        'update' => function ($model) {
            return true;
        },
        'delete' => function ($model) {
            return false;
        },
        'view' => function ($model) {
            return true;
        },
    ],

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title'    => '标示',
            'sortable' => false,
        ],
        'display_name' => [
            'title'    => '权限名称',
            'sortable' => false,
        ],
        'description' => [
            'title'    => '描述',
            'sortable' => false,
            'output'   => function ($value, $model) {
                return empty($value) ? 'N/A' : $value;
            },
        ],
        'roles' => [
            'title'  => '用户组',
            'output' => function ($value, $model) {
                $model->load('roles');
                $result = [];
                foreach ($model->roles as $role) {
                    $result[] = $role->display_name;
                }

                return empty($result) ? 'N/A' : implode($result, ' | ');
            },
            'sortable' => false,
        ],
        'operation' => [
            'title'    => '管理',
            'sortable' => false,
        ],
    ],

    'edit_fields' => [
        'name' => [
            'title' => '标示（请慎重修改）',
        ],
        'display_name' => [
            'title' => '权限名称',
        ],
        'description' => [
            'title' => '描述',
        ],
    ],
    'filters' => [
        'name' => [
            'title' => '标示',
        ],
        'display_name' => [
            'title' => '权限名称',
        ],
        'description' => [
            'title' => '描述',
        ],
    ],

    'actions' => [],
];
