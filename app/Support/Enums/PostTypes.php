<?php

namespace App\Support\Enums;

enum PostTypes: string implements IsEnum
{
    use NativeEnumsTrait;

    case POST = 'post';
    case PAGE = 'page';
    case CASE_STUDY = 'case_study';
    case LOGO = 'logo';

    public static function options(): array
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
            'success'   => 'post',
            'warning' => 'page',
            'danger'    => 'case_study',
            'primary'  => 'logo',
        ];
    }

    public static function getColor($status): string
    {
        return match ($status) {
            'post' => 'success',
            'page' => 'warning',
            'case_study' => 'danger',
            'logo' => 'primary',
        };
    }
}
