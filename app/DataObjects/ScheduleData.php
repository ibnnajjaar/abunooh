<?php

namespace App\DataObjects;

use App\Models\Shift;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use App\Support\Traits\CanConvertToArray;

class ScheduleData
{
    use CanConvertToArray;

    public function __construct(
        public Employee $employee,
        public Shift $shift,
        public Carbon $effective_from,
        public ?Carbon $effective_to = null,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        $shift = data_get($data, 'shift');
        if (! $shift instanceof Shift) {
            $shift = Shift::find($shift);
        }

        $employee = data_get($data, 'employee');
        if (! $employee instanceof Employee) {
            $employee = Employee::find($employee);
        }

        return new self(
            employee: $employee,
            shift: $shift,
            effective_from: $data['effective_from'] ? Carbon::parse($data['effective_from']) : null,
            effective_to: $data['effective_to'] ? Carbon::parse($data['effective_to']) : null,
        );
    }

}
