<?php

namespace App\Notifications;

use App\Http\Resources\Post\PostResource;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class PostLiked extends Notification
{
    use Queueable;

    private $post;

    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($post)
    {
        $this->message = 'liked your post!';
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_id' => Auth::id(),
            'username' => Auth::user()->username,
            'message' => $this->message,
            'post' => [
                'id' => $this->post->id,
                'cover' => $this->post->getFirstMedia('uploads')->getFullUrl('thumb'),
            ],
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_id' => Auth::id(),
            'username' => Auth::user()->username,
            'message' => $this->message,
            'post' => [
                'id' => $this->post->id,
                'cover' => $this->post->getFirstMedia('uploads')->getFullUrl('thumb'),
            ],
            'created_at' => Carbon::now()->diffForHumans(),
        ]);
    }
}
