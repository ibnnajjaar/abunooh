<?php

namespace App\Support\Enums;

enum PublishStatuses: string implements IsEnum
{
    use NativeEnumsTrait;

    case DRAFT = 'draft';
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case PUBLISHED = 'published';

    public static function options(): array
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
            'secondary'   => 'draft',
            'warning' => 'pending',
            'danger'    => 'rejected',
            'success'  => 'published',
        ];
    }

    public static function getColor($status): string
    {
        return match ($status) {
            'draft' => 'secondary',
            'pending' => 'warning',
            'rejected' => 'danger',
            'published' => 'success',
        };
    }
}
