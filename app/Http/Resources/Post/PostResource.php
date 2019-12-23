<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Member\MemberCollection;
use App\Http\Resources\Member\MemberResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
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
            'caption' => $this->caption,
            'author' => new MemberResource($this->author),
            'cover' => $this->getFirstMedia('uploads')->getFullUrl('thumb'),
            'media' => new MediaCollection($this->whenLoaded('media')),
            'likers' => new MemberCollection($this->likers),
            'comments' => new CommentCollection($this->whenLoaded('comments')),
            'comments_count' => $this->comments->count(),
            'isLiked' => $this->isLikedBy(Auth::user()),
            'isBookmarked' => $this->isBookmarkedBy(Auth::user()),
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
