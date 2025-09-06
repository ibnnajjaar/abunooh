<?php

namespace App\Rules;

use Closure;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class HasScheduleRule implements ValidationRule, DataAwareRule
{
    protected ?Carbon $date = null;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $employee = Employee::find($value);
        if (!$employee) {
            $fail('The selected employee does not exist.');
            return;
        }

        $schedule = $employee->schedules()->forDate($this->date)->exists();
        if (!$schedule) {
            $fail('The selected employee does not have a schedule for the selected date.');
        }
    }

    public function setData(array $data): static
    {
        $this->date = isset($data['data']['date']) ? Carbon::parse($data['data']['date']) : null;
        return $this;
    }
}
