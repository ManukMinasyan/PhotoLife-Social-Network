<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostCollection;
use App\Models\Member;
use App\Models\Post;
use Illuminate\Http\Request;

class ExplorerController extends Controller
{
    public function getPosts(Request $request)
    {

        $posts = Post::byPopularity()->orderBy('created_at', 'DESC')
            ->whereHas('author', function ($q) {
                $q->whereHas('modelSettings', function ($q) {
                    $q->where('settings->privacy', Member::$publicPrivacy);
                });
            });


        if ($request->has('tag')) {
            $posts = $posts->whereHas('tags', function ($q) use ($request) {
                $q->where('slug->en', $request->get('tag'));
            });
        }

        $posts = $posts->paginate(10);

        return new PostCollection($posts);
    }
}
