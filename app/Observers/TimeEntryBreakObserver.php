<?php

namespace App\Observers;

use App\Models\TimeEntryBreak;

class TimeEntryBreakObserver
{
    /**
     * Handle the TimeEntryBreak "saving" event.
     */
    public function saving(TimeEntryBreak $break): void
    {
        // Compute total break time in minutes only when break has ended
        if ($break->break_start_at && $break->break_end_at) {
            $break->total_break_time = $break->break_start_at->diffInMinutes($break->break_end_at);
        }
    }
}
