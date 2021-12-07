<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\MemberRequest;
use App\Http\Requests\Member\UpdatePasswordRequest;
use App\Http\Requests\Member\UpdatePrivacyRequest;
use App\Http\Resources\Member\AuthMemberResource;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\Notification\NotificationCollection;
use App\Http\Resources\Post\PostCollection;
use App\Models\FollowRequest;
use App\Models\Member;
use App\Models\Post;
use App\Notifications\MemberFollowed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthMember()
    {
//        [
//            'actions' => ['update', 'delete'],
//            'subject' => 'Post',
//            'conditions' => [
//                'contributorId' => '${user.contributorId}'
//            ]
//        ]
        return response()->json([
            'auth' => new AuthMemberResource(auth()->user()->load('follow_requests')),
            'rules' => [
                [
                    'actions' => ['update', 'delete'],
                    'subject' => ['Post'],
                ],
                [
                    'actions' => ['follow'],
                    'subject' => 'User',
                ],
            ],
        ]);
    }

    /**
     * @param MemberRequest $request
     * @return AuthMemberResource
     */
    public function updateAuthMember(MemberRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $user->clearMediaCollection('avatars');
            $extension = $avatar->getClientOriginalExtension();
            $name = uniqid('upload-').'.'.$extension;
            $avatar->move(public_path().'/uploads/avatars/', $name);
            $user->addMedia(public_path().'/uploads/avatars/'.$name)->toMediaCollection('avatars');

            $data['avatar'] = $name;
        }
        $user->update($data);

        return new AuthMemberResource(Auth::user());
    }

    /**
     * @param UpdatePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAuthMemberPassword(UpdatePasswordRequest $request)
    {
        Auth::user()->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['success' => true]);
    }

    /**
     * @param UpdatePrivacyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAuthMemberPrivacy(UpdatePrivacyRequest $request)
    {
        $data = $request->validated();

        Auth::user()->settings()->set('privacy', $data['privacy']);

        return response()->json(['success' => true]);
    }

    /**
     * @param $member
     * @return MemberResource
     */
    public function show($member)
    {
        $member = Member::findByUsername($member)->load(['posts' => function ($q) {
            $q->with('media')->orderBy('created_at', 'DESC');
        }]);

        return new MemberResource($member);
    }

    /**
     * @return NotificationCollection
     */
    public function getNotifications()
    {
        $notifications = Auth::user()->notifications()->paginate(10);

        return new NotificationCollection($notifications);
    }

    /**
     * @return NotificationCollection
     */
    public function markNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return $this->getNotifications();
    }

    /**
     * @return PostCollection
     */
    public function getBookmarkedPosts()
    {
        $posts = Auth::user()->bookmarks(Post::class)->orderBy('created_at', 'DESC')->paginate(12);

        return new PostCollection($posts);
    }

    /**
     * @param $member
     * @return MemberResource
     */
    public function follow($member)
    {
        $member = Member::findByUsername($member);

        if ($member->isPrivate() && ! $member->isFollowed()) {
            if ($member->isRequested()) {
                FollowRequest::where([
                    'follower_id' => Auth::id(),
                    'followable_id' => $member->id,
                ])->firstOrFail()->delete();
            } else {
                FollowRequest::firstOrCreate([
                    'follower_id' => Auth::id(),
                    'followable_id' => $member->id,
                ]);
            }
        } else {
            Auth::user()->toggleFollow($member);
        }

        /**
         * Notification | MemberFollowed
         * Older Notification Deleting Functionality
         */
        if ($member->isFollowed()) {
            $member->notify(new MemberFollowed(Auth::user()));
        } else {
            $olderNotification = $member->notifications->where('data.member.id', Auth::id())->first();
            if ($olderNotification) {
                $olderNotification->delete();
            }
        }

        return new MemberResource($member);
    }

    /**
     * @param FollowRequest $followRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmFollowRequest(FollowRequest $followRequest)
    {
        $followRequest = Auth::user()->follow_requests()->findOrFail($followRequest->id);
        $follower = Member::find($followRequest->follower_id);

        $follower->follow(Auth::user());
        $followRequest->delete();

        return response()->json(['success' => true]);
    }

    /**
     * @param FollowRequest $followRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFollowRequest(FollowRequest $followRequest)
    {
        $followRequest = Auth::user()->follow_requests()->findOrFail($followRequest->id);
        $followRequest->delete();

        return response()->json(['success' => true]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $user = auth()->user()->token();
        $user->revoke();

        return response()->json(['success' => true]);
    }
}
