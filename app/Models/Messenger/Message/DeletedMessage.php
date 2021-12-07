<?php

namespace App\Models\Messenger\Message;

use App\Models\Member;
use App\Models\Messenger\File\File;
use Illuminate\Database\Eloquent\Model;

class DeletedMessage extends Model
{
    protected $fillable = [
        'member_id', 'start_id', 'end_id',
    ];
}
