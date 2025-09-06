<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', '');
        $this->migrator->add('general.logo_url', 'images/logo.svg');
        $this->migrator->add('general.home_page_title', '');
        $this->migrator->add('general.home_page_description', '');
    }
};
