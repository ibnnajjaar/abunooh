<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_description', '');
        $this->migrator->add('general.socials', []);
        $this->migrator->delete('general.home_page_description', '');
    }
};
