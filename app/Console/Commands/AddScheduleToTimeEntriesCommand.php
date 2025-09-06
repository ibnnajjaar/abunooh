<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\TimeEntry;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class AddScheduleToTimeEntriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-schedule-to-time-entries {--chunk=500 : Number of records to process per chunk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill schedule_start_time and schedule_end_time on time entries using the employee\'s schedule for the entry date.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $chunk_size = (int) $this->option('chunk');

        $query = TimeEntry::query()
            ->where(function ($q) {
                $q->whereNull('schedule_start_time')
                  ->orWhereNull('schedule_end_time');
            });

        $total = (clone $query)->count();

        if ($total === 0) {
            $this->info('No time entries require backfilling.');
            return self::SUCCESS;
        }

        $this->info("Found {$total} time entries to backfill");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $updated_count = 0;
        $skipped_no_schedule = 0;
        $skipped_missing_employee = 0;

        $query->orderBy('id')
            ->chunkById($chunk_size, function ($entries) use (&$bar, &$updated_count, &$skipped_no_schedule, &$skipped_missing_employee) {
                /** @var TimeEntry $entry */
                foreach ($entries as $entry) {
                    $employee = $entry->employee;
                    if (! $employee instanceof Employee) {
                        $skipped_missing_employee++;
                        $bar->advance();
                        continue;
                    }

                    $date = $entry->date instanceof Carbon ? $entry->date : Carbon::parse($entry->date);

                    $schedule = $employee->schedules()->forDate($date)->first();

                    if (! $schedule) {
                        $skipped_no_schedule++;
                        $bar->advance();
                        continue;
                    }

                    $schedule_start_time = Carbon::parse($date)->setTimeFrom($schedule->start_time);
                    $schedule_end_time = Carbon::parse($date)->setTimeFrom($schedule->end_time);

                    $dirty = false;
                    if ($entry->schedule_start_time === null) {
                        $entry->schedule_start_time = $schedule_start_time;
                        $dirty = true;
                    }
                    if ($entry->schedule_end_time === null) {
                        $entry->schedule_end_time = $schedule_end_time;
                        $dirty = true;
                    }

                    if ($dirty) {
                        $entry->save();
                        $updated_count++;
                    }

                    $bar->advance();
                }
            });

        $bar->finish();
        $this->newLine(2);
        $this->info("Updated: {$updated_count}");
        $this->info("Skipped (no schedule found): {$skipped_no_schedule}");
        $this->info("Skipped (missing employee): {$skipped_missing_employee}");

        return self::SUCCESS;
    }
}
