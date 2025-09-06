<?php

namespace App\DataObjects;

use Illuminate\Support\Carbon;
use App\Support\Enums\EmployeeStatus;
use App\Support\Traits\CanConvertToArray;

class EmployeeData
{
    use CanConvertToArray;

    public function __construct(
        public string $name,
        public string $email,
        public ?string $system_identifier,
        public ?string $sap_number,
        public ?string $gov_id,
        public ?string $designation,
        public ?Carbon $email_verified_at,
        public ?EmployeeStatus $status,
        public ?int $supervisor_id,
    ) {

    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: data_get($data, 'name'),
            email: data_get($data, 'email'),
            system_identifier: data_get($data, 'system_identifier'),
            sap_number: data_get($data, 'sap_number'),
            gov_id: data_get($data, 'gov_id'),
            designation: data_get($data, 'designation'),
            email_verified_at: data_get($data, 'email_verified_at') ? Carbon::parse(data_get($data, 'email_verified_at')) : null,
            status: data_get($data, 'status'),
            supervisor_id: data_get($data, 'supervisor_id'),
        );
    }
}
