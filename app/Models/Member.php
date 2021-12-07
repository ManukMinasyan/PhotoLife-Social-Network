<?php

namespace App\Models;

use Glorand\Model\Settings\Traits\HasSettingsTable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanBookmark;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanLike;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Member extends Authenticatable implements HasMedia
{
    use HasApiTokens, InteractsWithMedia, HasSettingsTable, Notifiable, CanFollow, CanBeFollowed, CanLike, CanBookmark;

    protected $primaryKey = 'id';

    protected $guard = 'member';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = [
        'name', 'username', 'email', 'phone_number', 'website', 'bio', 'avatar', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $publicPrivacy = 'public';

    public $defaultSettings = [
        'privacy' => 'public',
    ];

    /**
     * @param $val
     * @return string
     */
    public function getAvatarAttribute($val)
    {
        if ($val) {
            $avatar = $this->getFirstMedia('avatars')->getFullUrl();
        } else {
            $avatar = asset('/assets/img/placeholders/128x128.png');
        }

        return $avatar;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'member_id');
    }

    public function follow_requests()
    {
        return $this->hasMany(FollowRequest::class, 'followable_id');
    }

    /**
     * @param $username
     * @return mixed
     */
    public static function findByUsername($username)
    {
        return self::where('username', $username)->firstOrFail();
    }

    /**
     * Scope a query to only include users where status public.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->whereHas('settings', function ($q) {
            $q->where('settings->privacy', 'public');
        });
    }

    /**
     * @return bool
     * @throws \Glorand\Model\Settings\Exceptions\ModelSettingsException
     */
    public function isPrivate()
    {
        return $this->settings()->get('privacy') === 'private';
    }

    /**
     * @return bool
     */
    public function isFollowed()
    {
        return $this->isFollowedBy(Auth::user());
    }

    /**
     * @return bool
     */
    public function isRequested()
    {
        return $this->follow_requests()->where('follower_id', Auth::id())->exists();
    }
}
