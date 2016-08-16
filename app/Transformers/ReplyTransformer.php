<?php

namespace App\Transformers;

/**
 * Class ReplyTransformer.
 */
class ReplyTransformer extends BaseTransformer
{
    /**
     * Resources that can be included if requested.
     *
     * @var array
     */
    protected $availableIncludes = ['user'];

    /**
     * Transform the \Reply entity.
     *
     * @param \Reply $model
     *
     * @return array
     */
    public function transformData($model)
    {
        return $model->toArray();
    }

    public function includeUser($model)
    {
        return $this->item($model->user, new UserTransformer());
    }
}
