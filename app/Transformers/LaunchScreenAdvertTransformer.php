<?php

namespace App\Transformers;

class LaunchScreenAdvertTransformer extends BaseTransformer
{
    public function transformData($model)
    {
        return [
            "id" => $model->id,
            "description" => $model->description,
            "image_small" => $model->image_small,
            "image_large" => $model->image_large,
            "type" => $model->type,
            "payload" => $model->payload,
            "display_time" => $model->display_time,
            "start_at" => $model->start_at,
            "expires_at" => $model->expires_at,
        ];
    }
}
