<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $posts = Post::with('media')->orderBy('created_at', 'DESC');

        if ($request->has('query')) {
            $query = $request->get('query');
            $posts->where(function ($q) use ($query) {
                $q->where('caption', 'like', "%$query%");
            });
        }

        $posts = $posts->paginate(15);

        return view('dashboard.posts.index', compact('posts'));
    }

    /**
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Post $post)
    {
        $post->delete();

        return redirect()->back()->with('status', 'Post deleted successfully!');
    }
}
