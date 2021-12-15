<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFollow\Traits\CanBeLiked;

class Comment extends Model
{
    use CanBeLiked;

    protected $fillable = [
        'body',
    ];

    public function author()
    {
        return $this->belongsTo(Member::class);
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
