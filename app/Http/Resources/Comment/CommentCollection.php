<?php

namespace App\Http\Resources\Comment;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (Comment $comment) {
            return (new CommentResource($comment));
        });

        return parent::toArray($request);
    }
}
