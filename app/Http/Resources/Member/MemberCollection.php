<?php

namespace App\Http\Resources\Member;

use App\Models\Member;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MemberCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (Member $member) {
            return new MemberResource($member);
        });

        return parent::toArray($request);
    }
}
