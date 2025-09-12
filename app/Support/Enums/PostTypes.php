<?php

namespace App\Support\Enums;

enum PostTypes: string implements IsEnum
{
    use NativeEnumsTrait;

    case POST = 'post';
    case PAGE = 'page';
    case CASE_STUDY = 'case_study';
    case LOGO = 'logo';

    public static function labels(): array
    {
        return [
            self::POST->value     => __("Post"),
            self::PAGE->value   => __("Page"),
            self::CASE_STUDY->value  => __("Case Study"),
            self::LOGO->value => __("Logo"),
        ];
    }

    public static function colors(): array
    {
        return [
            self::POST->value     => 'success',
            self::PAGE->value   => 'warning',
            self::CASE_STUDY->value  => 'danger',
            self::LOGO->value => 'primary',
        ];
    }

    public function getColor(): string
    {
        return self::colors()[$this->value] ?? 'secondary';
    }
}
