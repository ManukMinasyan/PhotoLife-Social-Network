<?php
/**
 * Created by PhpStorm.
 * User: Manuk
 * Date: 25/10/2019
 * Time: 02:04
 */

namespace App\Http\Controllers\Api\V1\Messenger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messenger\SendMessageRequest;
use App\Http\Resources\Messenger\ConversationCollection;
use App\Http\Resources\Messenger\ConversationResource;
use App\Http\Resources\Messenger\MessageCollection;
use App\Http\Resources\Messenger\MessageGroupByCollection;
use App\Models\Member;
use App\Models\Messenger\Conversation\Conversation;
use App\Repositories\Conversation\ConversationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{
    private $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * @param $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function startConversation($username)
    {
        $conversation = $this->conversationRepository->startConversationWith($username);

        return response()->json(['success' => true, 'conversation' => $conversation]);
    }

    /**
     * @return ConversationCollection
     */
    public function getConversations()
    {
        $conversations = $this->conversationRepository->all();

        return new ConversationCollection($conversations);
    }

    /**
     * @param $conversation_id
     * @return ConversationResource
     */
    public function getConversation($conversation_id)
    {
        $conversation = $this->conversationRepository->getById($conversation_id);

        return new ConversationResource($conversation);
    }

    /**
     * @param $conversation_id
     * @return MessageGroupByCollection
     */
    public function getMessages($conversation_id)
    {
        $messages = $this->conversationRepository->getMessages($conversation_id);

        return new MessageGroupByCollection($messages);
    }

    /**
     * @param $conversation_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteConversation($conversation_id)
    {
        $this->conversationRepository->deleteConversation($conversation_id);

        return response('', 200);
    }

    /**
     * @param SendMessageRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function sendMessage(SendMessageRequest $request)
    {
        $data = $request->validated();

        $this->conversationRepository->sendMessage($data['conversationId'], [
            'text' => $data['text']
        ]);

        return response('', 201);
    }
}