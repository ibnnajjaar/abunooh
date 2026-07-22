<?php

namespace App\Support\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public string $site_name;
    public string $site_description;
    public string $home_page_title;
    public string $logo_url;
    public array $socials;

    public static function group(): string
    {
        return 'general';
    }
}
