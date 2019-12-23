<?php

namespace App\Models\Messenger\Message;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use App\Models\Messenger\File\File;

class DeletedMessage extends Model
{
    protected $fillable = [
        'member_id', 'start_id', 'end_id'
    ];
}