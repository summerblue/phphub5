<?php

namespace App\Transformers;

class TopicTransformer extends BaseTransformer
{
    protected $availableIncludes = ['user', 'last_reply_user', 'replies', 'node'];

    protected $defaultIncludes = [];

    public function transformData($model)
    {
        $data = $model->toArray();
        $data['links'] = [
            'details_web_view' => route('topic.web_view', $model->id),
            'replies_web_view' => route('replies.web_view', $model->id),
            'web_url'          => trim(config('app.url'), '/').'/topics/'.$model->id,
        ];

        return $data;
    }

    public function includeUser($model)
    {
        return $this->item($model->user, new UserTransformer());
    }

    public function includeLastReplyUser($model)
    {
        return $this->item($model->lastReplyUser ?: $model->user, new UserTransformer());
    }

    public function includeReplies($model)
    {
        return $this->collection($model->replies, new ReplyTransformer());
    }

    public function includeNode($model)
    {
        return $this->item($model->category, new CategoryTransformer());
    }
}
