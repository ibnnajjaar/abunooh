<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Support\Traits\HasActivityLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $employee_id
 * @property Carbon|null $start_time Time of day (H:i:s)
 * @property Carbon|null $end_time Time of day (H:i:s)
 * @property Carbon|null $effective_from
 * @property Carbon|null $effective_to
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Schedule extends Model
{
    use HasActivityLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'start_time',
        'end_time',
        'effective_from',
        'effective_to',
    ];

    /**
     * Attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'effective_from' => 'datetime',
            'effective_to' => 'datetime',
        ];
    }

    /**
     * Employee relationship.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Cast start_time to a Carbon instance and store as H:i:s.
     */
    protected function startTime(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?Carbon => $value !== null ? Carbon::createFromFormat('H:i:s', $value) : null,
            set: function ($value): ?string {
                if ($value === null) {
                    return null;
                }
                if (! $value instanceof Carbon) {
                    $value = Carbon::parse($value);
                }
                return $value->format('H:i:s');
            }
        );
    }

    /**
     * Cast end_time to a Carbon instance and store as H:i:s.
     */
    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?Carbon => $value !== null ? Carbon::createFromFormat('H:i:s', $value) : null,
            set: function ($value): ?string {
                if ($value === null) {
                    return null;
                }
                if (! $value instanceof Carbon) {
                    $value = Carbon::parse($value);
                }
                return $value->format('H:i:s');
            }
        );
    }

    /**
     * Scope: schedules that are active at the current moment.
     */
    public function scopeCurrent($query)
    {
        return $this->scopeForDate($query, now());
    }

    /**
     * Scope: schedules that are active for a specific date/time.
     */
    public function scopeForDate($query, Carbon $date)
    {
        return $query->where('effective_from', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('effective_to')
                  ->orWhere('effective_to', '>=', $date);
            });
    }
}
