<?php

return [

    'user_model' => App\Models\User::class,

    'message_model' => App\Models\Message::class,

    'participant_model' => App\Models\Participant::class,

    'thread_model' => App\Models\Thread::class,

    /**
     * Define custom database table names - without prefixes.
     */
    'messages_table' => null,

    'participants_table' => null,

    'threads_table' => null,
];
