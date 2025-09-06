<?php

namespace App\Services;

use RuntimeException;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use App\DataObjects\ScheduleData;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ScheduleService
{
    public function store(ScheduleData $schedule_data): Model
    {
        // Basic validation: effective_to (if present) must be after or equal to effective_from
        if ($schedule_data->effective_to !== null && $schedule_data->effective_to->lt($schedule_data->effective_from)) {
            throw new RuntimeException('The schedule end must be after the start.');
        }

        try {
            return DB::transaction(function () use ($schedule_data) {
                $employee_id = $schedule_data->employee->id;
                $new_from = $schedule_data->effective_from;
                $new_to = $schedule_data->effective_to; // nullable => open ended

                // Determine an "infinite" end for overlap checks when the new schedule is open-ended
                $new_to_for_check = $new_to ?? Carbon::create(9999, 12, 31, 23, 59, 59);

                // Overlap rule for intervals [a1, a2] and [b1, b2] (inclusive), with null b2 meaning +infinity:
                // Overlap exists if (a1 <= b2) AND (b1 <= a2)
                $overlap_exists = $schedule_data->employee
                    ->schedules()
                    ->where('effective_from', '<=', $new_to_for_check)
                    ->where(function ($q) use ($new_from) {
                        $q->whereNull('effective_to')
                          ->orWhere('effective_to', '>=', $new_from);
                    })
                    ->exists();

                if ($overlap_exists) {
                    throw new RuntimeException('Schedule overlaps with an existing schedule for this employee.');
                }

                return Schedule::create([
                    'employee_id'    => $employee_id,
                    'start_time'     => $schedule_data->shift->start_time,
                    'end_time'       => $schedule_data->shift->end_time,
                    'effective_from' => $new_from,
                    'effective_to'   => $new_to,
                ]);
            });
        } catch (\Exception $e) {
            throw new RuntimeException('Failed to store schedule: ' . $e->getMessage());
        }
    }
}
