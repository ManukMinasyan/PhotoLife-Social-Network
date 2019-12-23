<?php

namespace App\Http\Resources\Member;

use App\Http\Resources\FollowRequest\FollowRequestCollection;
use App\Http\Resources\Post\PostCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthMemberResource extends JsonResource
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
            'email' => $this->email,
            'website' => $this->website,
            'phone_number' => $this->phone_number,
            'bio' => $this->bio,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'posts' => new PostCollection($this->whenLoaded('posts')),
            'unread_notifications_count' => $this->unreadNotifications->count(),
            'follow_requests' => new FollowRequestCollection($this->whenLoaded('follow_requests')),
            'followings_count' => $this->followings()->count(),
            'followers_count' => $this->followers()->count(),
            'privacy' => $this->settings()->get('privacy')
        ];
    }
}
