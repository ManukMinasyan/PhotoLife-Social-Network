<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\Models\Media;
use Overtrue\LaravelFollow\Traits\CanBeBookmarked;
use Overtrue\LaravelFollow\Traits\CanBeLiked;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use HasTags, HasMediaTrait, CanBeLiked, CanBeBookmarked;

    protected $fillable = [
        'caption', 'member_id'
    ];

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->extractVideoFrameAtSecond(5)
            ->performOnCollections('uploads');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    /**
     * Get all of the post's reports.
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Scope a query to only include public posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->whereHas('author', function ($q) {
            $q->public();
        });
    }

    /**
     * Scope a query to only include popular posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPopularity($query)
    {
        return $query->withCount('likers', 'comments')
            ->orderBy('likers_count', 'DESC')
            ->orderBy('comments_count', 'DESC');
    }
}
