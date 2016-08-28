<?php

namespace App\Transformers;

class CategoryTransformer extends BaseTransformer
{
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
        ];
    }
}
