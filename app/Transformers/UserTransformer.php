<?php

namespace App\Transformers;

use App\Models\User;

class UserTransformer extends BaseTransformer
{
    public function transformData($model)
    {
        $includable = [
            'id',
            'name',
            'avatar',
            'topic_count',
            'reply_count',
            'notification_count',
            'is_banned',
            'twitter_account',
            'company',
            'city',
            'email',
            'signature',
            'introduction',
            'github_name',
            'github_url',
            'real_name',
            'personal_website',
            'created_at',
            'updated_at',
        ];

        $user = array_only($model->toArray(), $includable);

        if ($model->getAttribute('avatar')) {
            $user['avatar'] = starts_with($model->avatar, 'http') ? $model->avatar : cdn('uploads/avatars/'.$model->avatar);
        }

        if ($model->getAttribute('links')) {
            $user['links'] = [
                'replies_web_view' => route('users.replies.web_view', $model->id),
            ];
        }

        return $user;
    }
}
