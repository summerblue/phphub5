<?php

namespace App\Http\ApiControllers;

use App\Transformers\NodeTransformer;

class NodesController extends Controller
{
    public function index()
    {
        $data = $this->nodes->all(['id', 'name', 'parent_node']);

        return $this->response()->collection($data, new NodeTransformer());
    }
}
