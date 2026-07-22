<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class () extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Hussain Afeef');
        $this->migrator->add('general.logo_url', 'images/logo.svg');
        $this->migrator->add('general.home_page_title', 'Personal Blog');
    }
};
