<?php

namespace App\Transformers;

use App\Notification;

class NotificationTransformer extends BaseTransformer
{
    protected $availableIncludes = ['from_user', 'topic', 'reply'];

    public function transformData($model)
    {
        return [
            "id" => $model->id,
            "type_msg" => $model->present()->lableUp,
            "message" => $model->present()->message(),
            "created_at" => $model->created_at->toDateTimeString(),
        ];
    }

    public function includeFromUser($model)
    {
        return $this->item($model->fromUser, new UserTransformer());
    }

    public function includeReply($model)
    {
        if ($model->reply === null) {
            return;
        }

        return $this->item($model->reply, new ReplyTransformer());
    }

    public function includeTopic($model)
    {
        if ($model->topic === null) {
            return;
        }

        return $this->item($model->topic, new TopicTransformer());
    }
}
