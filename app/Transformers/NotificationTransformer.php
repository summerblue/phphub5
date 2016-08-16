<?php

namespace App\Transformers;

use App\Notification;

/**
 * Class NotificationTransformer.
 */
class NotificationTransformer extends BaseTransformer
{
    /**
     * Resources that can be included if requested.
     *
     * @var array
     */
    protected $availableIncludes = ['from_user', 'topic', 'reply'];

    /**
     * Transform the \Notification entity.
     *
     * @param Notification $model
     *
     * @return array
     */
    public function transformData($model)
    {
        $data = $model->toArray();
        $data['type_msg'] = $model->typeMessage();
        $data['message'] = $model->message();

        return $data;
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
