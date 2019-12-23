<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Post\PostUpdateRequest;
use App\Models\Member;
use App\Notifications\PostLiked;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    /**
     * @return PostCollection
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'DESC')->with(['author', 'media', 'comments' => function ($q) {
            $q->orderBy('created_at', 'DESC')->with('replies')->limit(10);
        }])->whereHas('author', function ($q) {
            $q->where('id', Auth::id());
            $q->orWhereHas('followers', function ($q) {
                $q->where('member_id', Auth::id());
            });
        })->paginate(6);

        return new PostCollection($posts);
    }


    /**
     * @return PostCollection
     */
    public function postsByPopularity()
    {
        $posts = Post::byPopularity()->orderBy('created_at', 'DESC')
            ->whereHas('author', function ($q) {
                $q->whereHas('modelSettings', function ($q) {
                    $q->where('settings->privacy', Member::$publicPrivacy);
                });
            })->limit(9)->get();

        return new PostCollection($posts);
    }

    /**
     * @param $username
     * @return PostCollection
     */
    public function postsByUsername($username)
    {
        $member = Member::findByUsername($username);

        if ($member->isPrivate() && !$member->isFollowed() && $member->id !== Auth::id()) {
            return abort(403);
        }

        $posts = $member->posts()->orderBy('created_at', 'DESC')->with(['author', 'media', 'comments' => function ($q) {
            $q->orderBy('created_at', 'DESC')->with('replies')->limit(10);
        }])->paginate(12);

        return new PostCollection($posts);
    }

    /**
     * @param PostRequest $request
     * @return PostResource
     */
    public function store(PostRequest $request)
    {
        $data = $request->validated();

        $post = Auth::user()->posts()->create($data);

        $post->syncTagsWithType(get_hashtags_from_string($data['caption']), 'hashtags');

        foreach ($request->file('uploads') as $uploadItem) {
            $extension = $uploadItem->getClientOriginalExtension();
            $name = uniqid('upload-') . '.' . $extension;
            $uploadItem->move(public_path() . '/uploads/', $name);
            $post->addMedia(public_path() . '/uploads/' . $name)
                ->toMediaCollection('uploads');
        }

        return new PostResource($post->load('media', 'comments')->refresh());
    }

    /**
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        $post = $post->load(['media', 'comments' => function ($q) {
            $q->orderBy('created_at', 'DESC')->with('replies')->limit(10);
        }]);

        return new PostResource($post);
    }

    /**
     * @param PostUpdateRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $data = $request->validated();

        $post = Auth::user()->posts()->findOrFail($post->id);
        $post->update($data);

        $post->syncTagsWithType(get_hashtags_from_string($data['caption']), 'hashtags');

        $post = $post->load(['media', 'comments' => function ($q) {
            $q->orderBy('created_at', 'DESC')->with('replies')->limit(10);
        }]);

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Auth::user()->posts()->findOrFail($id);
        $post->delete();

        return response()->json(['success' => true]);
    }

    /**
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Post $post)
    {
        Auth::user()->toggleLike($post);

        if ($post->isLikedBy(Auth::id()) && $post->author->id != Auth::id()) {
            $post->author->notify(new PostLiked($post));
        } else {
            $olderNotification = $post->author->notifications->where('data.post.id', $post->id)->first();
            if ($olderNotification) {
                $olderNotification->delete();
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookmark(Post $post)
    {
        Auth::user()->toggleBookmark($post);

        return response()->json(['success' => true]);
    }
}
