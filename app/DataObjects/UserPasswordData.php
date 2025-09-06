<?php

namespace App\DataObjects;

use App\Support\Traits\CanConvertToArray;

class UserPasswordData
{
    use CanConvertToArray;

    public function __construct(
        public ?string $password,
        public ?string $password_confirmation,
        public bool $requires_password_update = false,
        public bool $email_password = false,
    ) {

    }

    public static function fromArray(array $data): self
    {
        return new self(
            password: data_get($data, 'password'),
            password_confirmation: data_get($data, 'password_confirmation'),
            requires_password_update: data_get($data, 'requires_password_update') ?? false,
            email_password: data_get($data, 'email_password') ?? false,
        );
    }
}
