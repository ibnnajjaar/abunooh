<?php

namespace App\Support\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public string $site_name;
    public string $home_page_title;
    public string $home_page_description;
    public string $logo_url;

    public static function group(): string
    {
        return 'general';
    }
}
