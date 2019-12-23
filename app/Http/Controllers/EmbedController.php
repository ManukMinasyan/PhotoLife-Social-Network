<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class EmbedController extends Controller
{
    /**
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function post(Post $post)
    {
        if ($post->author->settings()->get('privacy') !== 'public') {
            abort(403);
        }

        return view('app.embed.post')->with('post', $post);
    }
}
