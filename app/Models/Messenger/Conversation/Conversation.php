<?php

namespace App\Models\Messenger\Conversation;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use App\Models\Messenger\File\File;
use App\Models\Messenger\Message\Message;

class Conversation extends Model
{
    protected $fillable = [
        'first_member_id', 'second_member_id', 'is_accepted',
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * @return mixed
     */
    public function messages()
    {
        return $this->morphMany(Message::class, 'conversation');
    }

    /**
     * @return mixed
     */
    public function files()
    {
        return $this->morphMany(File::class, 'conversation');
    }

    /**
     * @return mixed
     */
    public function firstUser()
    {
        return $this->belongsTo(Member::class, 'first_member_id');
    }

    /**
     * @return mixed
     */
    public function secondUser()
    {
        return $this->belongsTo(Member::class, 'second_member_id');
    }
}