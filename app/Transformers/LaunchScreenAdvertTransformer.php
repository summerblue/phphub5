<?php

namespace App\Transformers;

/**
 * Class LaunchScreenAdvertTransformer.
 */
class LaunchScreenAdvertTransformer extends BaseTransformer
{
    /**
     * Transform the \LaunchScreenAdvert entity.
     *
     * @param $model
     *
     * @return array
     */
    public function transformData($model)
    {
        return $model->toArray();
    }
}
