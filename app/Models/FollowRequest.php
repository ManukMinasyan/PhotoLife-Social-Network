<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowRequest extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['follower_id', 'followable_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function follower()
    {
        return $this->belongsTo(Member::class, 'follower_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function followable()
    {
        return $this->belongsTo(Member::class, 'followable_id');
    }
}
