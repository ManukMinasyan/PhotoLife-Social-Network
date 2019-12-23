<?php

namespace App\Repositories\Conversation;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Events\NewConversationMessage;
use App\Events\VideoChatStart;
use App\Models\Messenger\Conversation\Conversation;
use App\Repositories\BaseRepository;
use App\Services\UploadManager;
use Illuminate\Support\Facades\Log;

class ConversationRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Conversation::class;
    /**
     * @var UploadManager
     */
    private $manager;

    /**
     * ConversationRepository constructor.
     *
     * @param UploadManager $manager
     */
    public function __construct(UploadManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        $conversations = $this->query()
            ->with([
                'firstUser',
                'secondUser'])
            ->where(function ($q) {
                $q->where('first_member_id', Auth::id());
                $q->orWhere('second_member_id', Auth::id());
            })
            ->whereHas('messages', function ($q) {
                $q->whereNull('deleted_by');
                $q->orWhere('deleted_by', '!=', Auth::id());
            })
            ->selectRaw("conversations.*, (SELECT MAX(created_at) from messages WHERE messages.conversation_id=conversations.id) as latest_message_on")
            ->orderBy("latest_message_on", "DESC")
            ->get();

        return $conversations;
    }

    /**
     * @param $user
     * @param $conversation
     *
     * @return bool
     */
    public function canJoinConversation($user, $conversation)
    {
        $thread = $this->find($conversation);

        if ($thread) {
            if (($thread->first_member_id == $user->id) || ($thread->second_member_id == $user->id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getById($id)
    {
        $conversation = $this->query()
            ->where(function ($q) {
                $q->where('first_member_id', Auth::id());
                $q->orWhere('second_member_id', Auth::id());
            })->with(['messages', 'messages.sender', 'messages.files', 'firstUser', 'secondUser', 'files'])
            ->findOrFail($id);

        $conversation->user = ($conversation->firstUser->id == Auth::id()) ? $conversation->secondUser : $conversation->firstUser;

        return $conversation;
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getMessages($id)
    {
        $conversation = $this->getById($id);

        $messages = $conversation->messages()
            ->where(function ($q) {
                $q->whereNull('deleted_by');
                $q->orWhere('deleted_by', '!=', Auth::id());
            })
            ->orderBy('created_at', 'DESC')
            ->with('sender')
            ->latest()
            ->paginate(15);

        return $messages;
    }

    /**
     * @param $username
     * @return bool
     */
    public function startConversationWith($username)
    {
        $secondMember = Member::findByUsername($username);

        $conversation = $this->query()->where(function ($q) use ($secondMember) {
            $q->where('first_member_id', Auth::id());
            $q->where('second_member_id', $secondMember->id);
        })->orWhere(function ($q) use ($secondMember) {
            $q->where('first_member_id', $secondMember->id);
            $q->where('second_member_id', Auth::id());
        })->first();

        if ($conversation) {
            return $conversation;
        }

        $created = $this->query()->firstOrCreate([
            'first_member_id' => Auth::id(),
            'second_member_id' => $secondMember->id,
        ]);

        if ($created) {
            return $created;
        }

        return false;
    }

    /**
     * @param $conversation_id
     */
    public function deleteConversation($conversation_id)
    {
        $conversation = $this->query()
            ->where('id', $conversation_id)
            ->where(function ($q) {
                $q->where('first_member_id', Auth::id());
                $q->orWhere('second_member_id', Auth::id());
            })->firstOrFail();


        $conversation->messages()->where(function ($q) {
            $q->whereNotNull('deleted_by');
            $q->where('deleted_by', '!=', Auth::id());
        })->delete();

        $conversation->messages()->whereNull('deleted_by')->update(['deleted_by' => Auth::id()]);
    }

    /**
     * @param $userId
     * @param $conversationId
     *
     * @return bool
     */
    public function acceptMessageRequest($userId, $conversationId)
    {
        if ($this->checkUserExist($userId, $conversationId)) {
            $conversation = $this->find($conversationId);
            $conversation->is_accepted = true;
            $conversation->save();

            return true;
        }

        return false;
    }

    /**
     * @param $userId
     * @param $conversationId
     *
     * @return bool
     */
    public function checkUserExist($userId, $conversationId)
    {
        $thread = $this->find($conversationId);

        if ($thread) {
            if (($thread->first_member_id == $userId) || ($thread->second_member_id == $userId)) {
                return true;
            }
        }

        return false;
    }

    public function sendFilesInConversation($conversationId, array $data)
    {
        return $this->sendMessage($conversationId, $data);
    }

    /**
     * @param $conversationId
     * @param array $data
     *
     * @return bool
     */
    public function sendMessage($conversationId, array $data)
    {
        $conversation = $this->getById($conversationId);

        $message = $conversation->messages()->create([
            'text' => $data['text'],
            'member_id' => Auth::id()
        ]);

        if ($message) {
            broadcast(new NewConversationMessage($message->load('sender')));
            return true;
        }

        return false;
    }
}
