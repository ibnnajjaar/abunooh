<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Observers\TimeEntryObserver;
use App\Support\Enums\TimeEntryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

/**
 * @property int $id
 * @property int $employee_id
 * @property Carbon $date
 * @property Carbon|null $clock_in_time
 * @property Carbon|null $clock_out_time
 * @property int $hours_worked
 * @property int $break_hours
 * @property int $hours_worked_without_break_hours
 * @property int $hours_worked_past_scheduled_shift
 * @property float|null $clock_in_latitude
 * @property float|null $clock_in_longitude
 * @property float|null $clock_out_latitude
 * @property float|null $clock_out_longitude
 * @property string $status
 * @property Carbon|null $approved_at
 * @property int|null $approved_by_id
 */
#[ObservedBy([TimeEntryObserver::class])]
class TimeEntry extends Model
{

    protected $fillable = [
        'date',
        'schedule_start_time',
        'schedule_end_time',
        'clock_in_time',
        'clock_out_time',
        'hours_worked',
        'break_hours',
        'hours_worked_without_break_hours',
        'hours_worked_past_scheduled_shift',
        'status',
        'approved_at',
        'employee_id'
    ];

    protected $attributes = [
        'status' => TimeEntryStatus::Pending,
    ];

    protected $casts = [
        'date'           => 'date:Y-m-d',
        'schedule_start_time' => 'datetime',
        'schedule_end_time' => 'datetime',
        'clock_in_time'  => 'datetime',
        'clock_out_time' => 'datetime',
        'approved_at'    => 'datetime',
        'status' => TimeEntryStatus::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function breaks(): HasMany
    {
        return $this->hasMany(TimeEntryBreak::class);
    }

    #[Scope]
    public function userVisible($query, ?Authenticatable $user = null): void
    {
        $user ??= auth()->user();
        if ($user instanceof Employee) {
            $query->where('employee_id', $user->id);
            return;
        }
        /* @var User $user */
        if ($user->hasPermissionTo('view any time entry')) {
            return;
        }

        if ($user->hasPermissionTo('view time entries')) {
            $query->join('employees', 'employees.id', '=', 'time_entries.employee_id')
                    ->where('employees.supervisor_id', $user->id);

            return;
        }

        $query->where('time_entries.id', 0);
    }

    public function isCurrentlyClockedIn(): bool
    {
        return $this->clock_in_time && $this->clock_out_time === null;
    }

    public function getFormattedHoursWorkedAttribute(): ?string
    {
        return format_minutes_for_display($this->hours_worked);
    }

    public function getFormattedBreakHoursAttribute(): ?string
    {
        return format_minutes_for_display($this->break_hours);
    }

    public function getFormattedHoursWorkedWithoutBreakAttribute(): string
    {
        return format_minutes_for_display($this->hours_worked_without_break_hours);
    }

    public function getFormattedOverTimeAttribute(): string
    {
        return format_minutes_as_hour_minute($this->hours_worked_past_scheduled_shift);
    }

    public function getFormattedScheduleAttribute(): string
    {
        return $this->schedule_start_time?->format('H:i') . ' - ' . $this->schedule_end_time?->format('H:i');
    }

    public function getFormattedClockInsAttribute(): string
    {
        return $this->clock_in_time?->format('H:i') . ' - ' . $this->clock_out_time?->format('H:i');
    }
}
