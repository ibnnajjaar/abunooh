<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use function Termwind\parse;

class Activity extends SpatieActivity
{

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($activity) {
            $activity->ip = get_request_ip();
        });
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        if ($date = $this->created_at) {
            return __(':rel (:time)', ['rel' => $date->diffForHumans(), 'time' => $date->format('j M Y @ H:i')]);
        }

        return __('Log #:id', ['id' => $this->id]);
    }

    public function getFormattedSubjectMetaAttribute(): string
    {
        return "[{$this->subject_type}: #{$this->subject_id}]";
    }

    public function getFormattedCauserMetaAttribute(): string
    {
        return "[{$this->causer_type}: #{$this->causer_id}]";
    }
}
