<?php

namespace App\Http\Resources\Post;

use App\Models\Post;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (Post $post) {
            return (new PostResource($post));
        });

        return parent::toArray($request);
    }
}
