<?php

namespace App\Support\Enums;

enum UserStatus: string
{
    use NativeEnumsTrait;

    case Active = 'active';
    case Inactive = 'inactive';

    public static function labels(): array
    {
        return [
            self::Active->value => 'Active',
            self::Inactive->value => 'Inactive',
        ];
    }

    public static function colors(): array
    {
        return [
            self::Active->value   => 'success',
            self::Inactive->value => 'danger',
        ];
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }

    public function getColor(): string
    {
        return self::colors()[$this->value];
    }


}
