<?php

namespace Database\Factories;

use App\Models\TimeEntryBreak;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\TimeEntryBreak>
 */
class TimeEntryBreakFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TimeEntryBreak>
     */
    protected $model = TimeEntryBreak::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure there is a parent time entry, using the existing TimeEntryFactory directly
        $timeEntry = \Database\Factories\TimeEntryFactory::new()->create();

        // Choose a sensible break window within the time entry's clock-in/out period
        $clockIn = $timeEntry->clock_in_time ?? now()->subHours(8);
        $clockOut = $timeEntry->clock_out_time ?? now();

        // Avoid invalid ranges
        if ($clockOut <= $clockIn) {
            $clockOut = (clone $clockIn)->modify('+4 hours');
        }

        // Pick a break start between clock in and a bit before clock out
        $start = fake()->dateTimeBetween($clockIn, (clone $clockOut)->modify('-15 minutes'));

        // 85% of the time, the break is closed with an end time
        $hasEnd = fake()->boolean(85);
        $duration = fake()->numberBetween(5, 60); // minutes
        $end = $hasEnd ? (clone $start)->modify("+{$duration} minutes") : null;

        // Ensure end does not exceed clockOut
        if ($end && $end > $clockOut) {
            $end = (clone $clockOut);
            $duration = max(0, (int) round(($end->getTimestamp() - $start->getTimestamp()) / 60));
        }

        $lat = fn () => fake()->optional(0.7)->randomFloat(8, -90, 90);
        $lng = fn () => fake()->optional(0.7)->randomFloat(8, -180, 180);

        return [
            'time_entry_id' => $timeEntry->id,

            'break_start_at' => $start,
            'break_end_at' => $end,

            'note' => fake()->optional(0.3)->sentence(),

            // minutes
            'total_break_time' => $end ? $duration : null,

            'break_start_latitude' => $lat(),
            'break_start_longitude' => $lng(),
            'break_end_latitude' => $end ? $lat() : null,
            'break_end_longitude' => $end ? $lng() : null,
        ];
    }

    /**
     * State for an open (ongoing) break without an end time.
     */
    public function open(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'break_end_at' => null,
                'total_break_time' => null,
                'break_end_latitude' => null,
                'break_end_longitude' => null,
            ];
        });
    }

    /**
     * State for a closed (finished) break that ensures an end time and computed duration.
     */
    public function closed(): static
    {
        return $this->state(function (array $attributes) {
            $start = $attributes['break_start_at'] ?? now();
            $duration = fake()->numberBetween(5, 60);
            $end = (clone $start)->modify("+{$duration} minutes");

            return [
                'break_end_at' => $end,
                'total_break_time' => $duration,
                'break_end_latitude' => fake()->optional(0.7)->randomFloat(8, -90, 90),
                'break_end_longitude' => fake()->optional(0.7)->randomFloat(8, -180, 180),
            ];
        });
    }
}
