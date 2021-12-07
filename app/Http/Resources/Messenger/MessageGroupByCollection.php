<?php

namespace App\Http\Resources\Messenger;

use App\Models\Messenger\Message\Message;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;

class MessageGroupByCollection extends ResourceCollection
{
    private $pagination;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('M d, Y h:00');
        }, 'id')->transform(function ($messages, $group_date) {
            return [
                'group_date' => $group_date,
                'messages' => new MessageCollection($messages),
            ];
        })->values()->toArray();
    }
}
