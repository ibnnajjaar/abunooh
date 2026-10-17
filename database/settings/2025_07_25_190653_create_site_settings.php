<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class () extends SettingsMigration {
    public function up(): void
    {
        // The legacy production database already has these settings
        if (! $this->migrator->exists('general.site_name')) {
            $this->migrator->add('general.site_name', 'Hussain Afeef');
        }

        if (! $this->migrator->exists('general.logo_url')) {
            $this->migrator->add('general.logo_url', 'images/logo.svg');
        }

        if (! $this->migrator->exists('general.home_page_title')) {
            $this->migrator->add('general.home_page_title', 'Personal Blog');
        }
    }
};
