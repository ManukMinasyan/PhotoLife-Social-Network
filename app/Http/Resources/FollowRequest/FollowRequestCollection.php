<?php

namespace App\Http\Resources\FollowRequest;

use App\Models\FollowRequest;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FollowRequestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (FollowRequest $followRequest) {
            return (new FollowRequestResource($followRequest));
        });

        return parent::toArray($request);
    }
}
