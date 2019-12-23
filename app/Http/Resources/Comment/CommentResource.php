<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\Member\MemberCollection;
use App\Http\Resources\Member\MemberResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'author' => new MemberResource($this->author),
            'replies' => new CommentCollection($this->whenLoaded('replies')),
            'likers' => new MemberCollection($this->likers),
            'isLiked' => $this->isLikedBy(Auth::user()),
            'created_at' => $this->created_at->diffForHumans(null, false, true)
        ];
    }
}
