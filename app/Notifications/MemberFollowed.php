<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class MemberFollowed extends Notification
{
    use Queueable;

    private $member;

    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($member)
    {
        $this->message = 'started following you.';
        $this->member = $member;
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
            'member' => [
                'id' => $this->member->id,
                'username' => $this->member->username,
                'avatar' => $this->member->avatar,
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
            'member' => [
                'id' => $this->member->id,
                'username' => $this->member->username,
                'avatar' => $this->member->avatar,
            ],
            'created_at' => Carbon::now()->diffForHumans(),
        ]);
    }
}
