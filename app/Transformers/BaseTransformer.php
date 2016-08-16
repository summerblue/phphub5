<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    public function transform(Model $model)
    {
        $transformData = $this->transformData($model);

        $data = array_filter($transformData, function ($v) {
            if (is_null($v)) {
                return false;
            }

            return true;
        });

        // 转换 null 字段为空字符串
        foreach (array_keys($data) as $key) {
            if (! isset($data[$key])) {
                $data[$key] = '';
                continue;
            }
            if (is_null($data[$key])) {
                $data[$key] = '';
            }
        }

        return $data;
    }

    abstract public function transformData($model);
}
