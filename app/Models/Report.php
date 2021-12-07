<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    private $reportable_type = [
        'post' => Post::class,
    ];

    protected $fillable = [
        'member_id',
        'report_type_id',
    ];

    /**
     * Get all of the models that own reports.
     */
    public function reportable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(ReportType::class, 'report_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * @param $q
     * @param $type
     */
    public function scopeWhereType($q, $type)
    {
        if (isset($this->reportable_type[$type])) {
            $q->where('reportable_type', $this->reportable_type[$type]);
        }
    }
}
