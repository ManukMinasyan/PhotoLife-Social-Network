<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'mime_type' => $this->mime_type,
            'full_url' => $this->getFullUrl(),
            'thumb_url' => file_exists($this->getPath('thumb'))
                ? $this->getFullUrl('thumb')
                : asset('images/video-none-background.jpg'),
            'created_at' => $this->created_at
        ];
    }
}
