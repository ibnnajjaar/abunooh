<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Employee;
use App\Models\TimeEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TimeEntry>
     */
    protected $model = TimeEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-30 days', 'now');

        // Clock-in between 7:00 and 11:00 on the given date
        $clockIn = (clone $date)->setTime(fake()->numberBetween(7, 11), [0, 15, 30, 45][fake()->numberBetween(0, 3)]);

        // Work between 6 and 10 hours
        $workedMinutes = fake()->numberBetween(6 * 60, 10 * 60);

        // Break between 0 and 90 minutes but not exceeding worked minutes
        $breakMinutes = min(fake()->numberBetween(0, 90), $workedMinutes);

        $clockOut = (clone $clockIn)->modify("+{$workedMinutes} minutes");

        $workedWithoutBreak = max($workedMinutes - $breakMinutes, 0);

        // Occasionally add some overtime past scheduled shift (0-60 minutes)
        $pastScheduled = fake()->numberBetween(0, 60);

        // Random status
        $status = fake()->randomElement(['pending', 'approved', 'rejected']);

        // Geolocation helpers
        $lat = fn () => fake()->optional(0.7)->randomFloat(8, -90, 90);
        $lng = fn () => fake()->optional(0.7)->randomFloat(8, -180, 180);

        return [
            'employee_id' => function () {
                $employee = Employee::query()->inRandomOrder()->first();
                if (! $employee) {
                    // Fallback creation to avoid requiring an Employee factory
                    $employee = Employee::query()->create([
                        'name' => fake()->name(),
                        'email' => fake()->unique()->safeEmail(),
                        'password' => 'password', // hashed by model cast
                    ]);
                }
                return $employee->id;
            },

            'date' => $clockIn->format('Y-m-d'),
            'clock_in_time' => $clockIn,
            'clock_out_time' => $clockOut,

            // Minutes
            'hours_worked' => $workedMinutes,
            'break_hours' => $breakMinutes,
            'hours_worked_without_break_hours' => $workedWithoutBreak,
            'hours_worked_past_scheduled_shift' => $pastScheduled,

            // Locations
            'clock_in_latitude' => $lat(),
            'clock_in_longitude' => $lng(),
            'clock_out_latitude' => $lat(),
            'clock_out_longitude' => $lng(),

            // Status & approval
            'status' => $status,
            'approved_at' => function () use ($status, $clockOut) {
                return $status === 'approved' ? (clone $clockOut)->modify('+' . fake()->numberBetween(0, 120) . ' minutes') : null;
            },
            'approved_by_id' => function (array $attributes) {
                if (($attributes['approved_at'] ?? null) !== null) {
                    return User::factory()->create()->id;
                }
                return null;
            },
        ];
    }

    /**
     * Indicate the time entry is approved now by a new user.
     */
    public function approved(): static
    {
        return $this->state(function (array $attributes) {
            $approvedAt = now();

            return [
                'status' => 'approved',
                'approved_at' => $approvedAt,
                'approved_by_id' => User::factory(),
            ];
        });
    }

    /**
     * Indicate the time entry is pending (not approved).
     */
    public function pending(): static
    {
        return $this->state([
            'status' => 'pending',
            'approved_at' => null,
            'approved_by_id' => null,
        ]);
    }
}
