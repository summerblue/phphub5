<?php

return [

    'replies_perpage'         => 200,
    'actived_time_for_update' => 'actived_time_for_update',
    'actived_time_data'       => 'actived_time_data',

    'blog_category_id'       => env('BLOG_CATEGORY_ID'),
    'life_category_id'       => env('LIFE_CATEGORY_ID'),
    'qa_category_id'         => env('QA_CATEGORY_ID'),
    'hunt_category_id'         => env('HUNT_CATEGORY_ID'),

    'wiki_topic_id'          => env('WIKI_TOPIC_ID') ?:1,
    'admin_board_cid'        => env('ADMIN_BOARD_CID') ?:0,

    'notify_delay'           => 180,
];
