<?php

namespace App\Http\Resources\Messenger;

use App\Models\Messenger\Message\Message;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (Message $message) {
            return new MessageResource($message);
        });

        return parent::toArray($request);
    }
}
