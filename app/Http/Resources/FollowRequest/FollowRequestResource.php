<?php

namespace App\Http\Resources\FollowRequest;

use App\Http\Resources\Member\MemberResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'follower' => new MemberResource($this->follower)
        ];
    }
}
