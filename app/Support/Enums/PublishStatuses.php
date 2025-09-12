<?php

namespace App\Support\Enums;

enum PublishStatuses: string implements IsEnum
{
    use NativeEnumsTrait;

    case DRAFT = 'draft';
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case PUBLISHED = 'published';

    public static function labels(): array
    {
        return [
            self::DRAFT->value     => __("Draft"),
            self::PENDING->value   => __("Pending"),
            self::REJECTED->value  => __("Rejected"),
            self::PUBLISHED->value => __("Published"),
        ];
    }

    public static function colors(): array
    {
        return [
            self::DRAFT->value => 'secondary',
            self::PENDING->value => 'warning',
            self::REJECTED->value => 'danger',
            self::PUBLISHED->value => 'success',
        ];
    }

    public function getColor(): string
    {
        return self::colors()[$this->value] ?? 'secondary';
    }
}
