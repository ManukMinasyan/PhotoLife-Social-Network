<?php
/**
 * Created by PhpStorm.
 * User: Manuk
 * Date: 25/10/2019
 * Time: 01:28
 */

namespace App\Models\Messenger\File;

use App\Models\Member;
use App\Models\Messenger\Message\Message;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'message_id', 'member_id', 'name',
    ];

    protected $appends = [
        'file_details',
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
    public function conversation()
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    /**
     * @return mixed
     */
    public function sender()
    {
        return $this->belongsTo(Member::class, 'member_id_id');
    }

    public function getFileDetailsAttribute()
    {
        return get_file_details($this->name);
    }
}
