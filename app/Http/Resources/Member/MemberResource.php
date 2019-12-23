<?php

namespace App\Http\Resources\Member;

use App\Http\Resources\Post\PostCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'bio' => $this->bio,
            'website' => $this->website,
            'avatar' => $this->avatar,
            'posts' => new PostCollection($this->whenLoaded('posts')),
            'isFollowed' => $this->isFollowed(),
            'isRequested' => $this->isRequested(),
            'followings_count' => $this->followings()->count(),
            'followers_count' => $this->followers()->count(),
            'privacy' => $this->settings()->get('privacy')
        ];
    }
}
