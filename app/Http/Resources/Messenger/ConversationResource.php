<?php

namespace App\Http\Resources\Messenger;

use App\Http\Resources\Member\MemberResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ConversationResource extends JsonResource
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
            'last_message' => new MessageResource($this->messages->last()),
            'user' => new MemberResource(($this->firstUser->id == Auth::id()) ? $this->secondUser : $this->firstUser),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
