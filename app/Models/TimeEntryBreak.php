<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\TimeEntryBreakObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

/**
 * @property int $id
 * @property int $time_entry_id
 * @property string $break_start_at
 * @property string|null $break_end_at
 * @property string|null $note
 * @property int|null $total_break_time
 * @property float|null $break_start_latitude
 * @property float|null $break_start_longitude
 * @property float|null $break_end_latitude
 * @property float|null $break_end_longitude
 */
#[ObservedBy([TimeEntryBreakObserver::class])]
class TimeEntryBreak extends Model
{
    protected $fillable = [
        'break_start_at',
        'break_end_at',
        'note',
        'total_break_time',
        'break_start_latitude',
        'break_start_longitude',
        'break_end_latitude',
        'break_end_longitude',
    ];

    protected $casts = [
        'break_start_at' => 'datetime',
        'break_end_at'   => 'datetime',
    ];

    public function timeEntry(): BelongsTo
    {
        return $this->belongsTo(TimeEntry::class);
    }

    #[Scope]
    protected function ended(Builder $query): void
    {
        $query->whereNotNull('break_end_at');
    }
}
