<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function ($notification) {
            return (new NotificationResource($notification));
        });

        return parent::toArray($request);
    }
}
