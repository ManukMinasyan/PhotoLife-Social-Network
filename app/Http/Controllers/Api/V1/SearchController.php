<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Spatie\Tags\Tag;

class SearchController extends Controller
{
    public function index($query)
    {
        $results = collect();

        $members = Member::where(function ($q) use ($query) {
            $q->where('name', 'like', '%'.$query.'%');
            $q->orWhere('username', 'like', '%'.$query.'%');
        })->limit(20)->get();

        $tags = Tag::where(function ($q) use ($query) {
            $q->where('name->en', 'like', '%'.$query.'%');
        })->limit(20)->get();

        if ($members->count()) {
            $results = $results->merge($members->transform(function ($m) {
                $m->type = 'member';

                return $m;
            }));
        }

        if ($tags->count()) {
            $results = $results->merge($tags);
        }

        return response()->json(['data' => $results]);
    }
}
