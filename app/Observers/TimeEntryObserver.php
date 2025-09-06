<?php

namespace App\Observers;

use App\Models\TimeEntry;
use Illuminate\Support\Carbon;
use App\Actions\CalculateOverTimeAction;

class TimeEntryObserver
{


    /**
     * Handle the TimeEntry "creating" event.
     */
    public function creating(TimeEntry $time_entry): void
    {
        // Ensure date is set if clock_in_time is present and date is missing
        if (empty($time_entry->date) && $time_entry->clock_in_time) {
            $time_entry->date = $time_entry->clock_in_time->format('Y-m-d');
        }
    }

    /**
     * Handle the TimeEntry "saving" event.
     */
    public function saving(TimeEntry $time_entry): void
    {
        // Compute worked minutes only when both in/out are available
        if (! $time_entry->clock_in_time || ! $time_entry->clock_out_time) {
            return;
        }

        $worked_minutes = $time_entry->clock_in_time->diffInMinutes($time_entry->clock_out_time);
        $time_entry->loadMissing('breaks');
        $breakMinutes = $time_entry->breaks->sum('total_break_time');

        $time_entry->hours_worked = max(0, (int) $worked_minutes);
        $time_entry->break_hours = max(0, (int) $breakMinutes);
        $time_entry->hours_worked_without_break_hours = max(0, (int) ($worked_minutes - $breakMinutes));

        // Calculate overtime
        $scheduled_minutes = (int)config('defaults.work_hours');
        $over_time = max(0, $worked_minutes - $scheduled_minutes);
        $schedule_end_at = Carbon::parse($time_entry->clock_in_time->format('Y-m-d') . ' ' . config('defaults.schedule_end_at'));
        if ($time_entry->clock_in_time->greaterThan($schedule_end_at)
            || in_array($time_entry->clock_in_time->dayOfWeek, [5, 6])
        ) {
            $over_time =  $worked_minutes;
        }
        $time_entry->hours_worked_past_scheduled_shift = $over_time;
    }
}
