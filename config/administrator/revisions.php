<?php

use App\Models\Revision;

return [
    'title'   => '操作记录',
    'heading' => '操作记录',
    'single'  => '操作记录',
    'model'   => Revision::class,

    'action_permissions' => [
        'create' => function ($model) {
            return false;
        },
        'update' => function ($model) {
            return false;
        },
        'delete' => function ($model) {
            return false;
        },
    ],

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'revisionable_type' => [
            'title' => '记录的 Model'
        ],
        'revisionable_id' => [
            'title'    => '记录的 id',
            'sortable' => false,
        ],
        'user' => [
            'title'        => '操作用户',
            'relationship' => 'user',
            'select'       => "(:table).name",
        ],
        'key' => [
            'title' => '操作的字段',
        ],
        'logs' => [
            'title'  => '操作的 Log',
            'output' => function ($value, $model) {
                $html = "<div style='text-align:left;'>
                            <div style='text-indent:2em'>'old_value'&nbsp;&nbsp;&nbsp;=> '$model->old_value',</div>
                            <div style='text-indent:2em'>'new_value' => '$model->new_value'</div>
                        </div>";
                return $html;
            }
        ],
        'created_at' => [
            'title' => '操作时间'
        ]
    ],

    'edit_fields' => [
        'id' => [
            'title' => 'id'
        ]
    ],
    'filters' => [
        'revisionable_type' => [
            'title' => '记录的 Model'
        ],
        'revisionable_id' => [
            'title' => '记录的 id',
        ],
        'user' => [
            'title'  => '操作用户',
            'type'   => 'relationship',
            'select' => "(:table).name",
        ],
        'key' => [
            'title' => '操作的字段',
        ],
        'old_value' => [
            'title' => '修改前的值'
        ],
        'new_value' => [
            'title' => '修改后的值'
        ],
    ],

    'actions' => [],
];
