<?php

namespace App\Http\Resources\Messenger;

use App\Http\Resources\Member\MemberResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'text' => $this->text,
            'sender' => new MemberResource($this->whenLoaded('sender')),
            'created_at' => $this->created_at->diffForHumans(),
            'created_at_fully' => $this->created_at->format('F d, Y \\a\\t H:i A'),
        ];
    }
}
