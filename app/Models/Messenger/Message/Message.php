<?php

namespace App\Models\Messenger\Message;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use App\Models\Messenger\File\File;

class Message extends Model
{
    protected $fillable = [
        'member_id', 'text', 'deleted_by'
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('laravel-video-chat.table.messages_table');
    }

    /**
     * @return mixed
     */
    public function conversation()
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function sender()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * @return mixed
     */
    public function files()
    {
        return $this->hasMany(File::class, 'message_id');
    }
}