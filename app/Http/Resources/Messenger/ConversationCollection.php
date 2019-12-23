<?php

namespace App\Http\Resources\Messenger;

use App\Models\Messenger\Conversation\Conversation;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConversationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (Conversation $conversation) {
            return (new ConversationResource($conversation));
        });

        return parent::toArray($request);
    }
}
