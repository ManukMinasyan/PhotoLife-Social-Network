<?php

namespace App\Http\Resources\Page;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function ($page) {
            return (new PageResource($page));
        });

        return parent::toArray($request);
    }
}
