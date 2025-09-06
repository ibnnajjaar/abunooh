<?php

namespace App\DataObjects;

use Illuminate\Support\Carbon;
use App\Support\Traits\CanConvertToArray;

class UserData
{
    use CanConvertToArray;

    public function __construct(
        public string $name,
        public string $email,
        public ?Carbon $email_verified_at,
    ) {

    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: data_get($data, 'name'),
            email: data_get($data, 'email'),
            email_verified_at: data_get($data, 'email_verified_at') ? Carbon::parse(data_get($data, 'email_verified_at')) : null,
        );
    }
}
