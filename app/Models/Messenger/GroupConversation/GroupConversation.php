<?php
/**
 * Created by PhpStorm.
 * User: Manuk
 * Date: 25/10/2019
 * Time: 01:27
 */

namespace App\Models\Messenger\GroupConversation;

use App\Models\Member;
use App\Models\Messenger\File\File;
use App\Models\Messenger\Message\Message;
use Illuminate\Database\Eloquent\Model;

class GroupConversation extends Model
{
    protected $fillable = [
        'name',
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
    public function users()
    {
        return $this->belongsToMany(
            Member::class,
            'group_users',
            'group_conversation_id',
            'member_id'
        );
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
}
