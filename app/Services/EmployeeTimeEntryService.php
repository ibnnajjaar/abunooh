<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\TimeEntry;
use App\Models\TimeEntryBreak;
use Ramsey\Uuid\Type\Time;
use Illuminate\Container\Attributes\CurrentUser;

class EmployeeTimeEntryService
{

    /**
     * @throws \Exception
     */
    public function clockIn(Employee $employee): TimeEntry
    {
        if (! $this->canClockIn($employee)) {
            throw new \Exception('Cannot clock in. You are already clocked in.');
        }

        $time_entry = new TimeEntry();
        $time_entry->employee()->associate($employee);
        $time_entry->clock_in_time = now();
        $time_entry->save();

        return $time_entry;
    }

    public function canClockIn(Employee $employee): bool
    {
        return $employee->timeEntries()->whereNotNull('clock_in_time')->whereNull('clock_out_time')->doesntExist();
    }

    public function clockOut(Employee $employee): TimeEntry
    {
        if (! $this->canClockOut($employee)) {
            throw new \Exception('Cannot clock out. You are not clocked in.');
        }

        $time_entry = $employee->timeEntries()->whereNotNull('clock_in_time')->whereNull('clock_out_time')->first();

        // End any ongoing breaks
        $time_entry->breaks()
                   ->whereNull('break_end_at')
                   ->whereNotNull('break_start_at')
                   ->update(['break_end_at' => now()]);

        $time_entry->clock_out_time = now();
        $time_entry->save();

        return $time_entry;
    }

    public function canClockOut(Employee $employee): bool
    {
        return $employee->timeEntries()->whereNotNull('clock_in_time')->whereNull('clock_out_time')->exists();
    }

    public function breakout(TimeEntry $timeEntry): TimeEntryBreak
    {
        if (! $this->canBreakOut($timeEntry)) {
            throw new \Exception('Cannot break out. You are not clocked in or already have a break out in progress.');
        }

        $break = new TimeEntryBreak();
        $break->timeEntry()->associate($timeEntry);
        $break->break_start_at = now();
        $break->save();

        return $break;
    }

    public function canBreakOut(TimeEntry $timeEntry): bool
    {
        return $timeEntry->isCurrentlyClockedIn()
            && $timeEntry->breaks()->whereNull('break_end_at')->doesntExist();
    }

    public function breakIn(TimeEntryBreak $break): TimeEntryBreak
    {
        if (! $this->canBreakIn($break)) {
            throw new \Exception('Cannot break in. No active break found.');
        }

        $break->break_end_at = now();
        $break->save();

        return $break;
    }

    public function canBreakIn(TimeEntryBreak $break): bool
    {
        return $break->break_start_at && ! $break->break_end_at;
    }

}
