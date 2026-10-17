<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('general.site_description')) {
            $this->migrator->add('general.site_description', '');
        }

        if (! $this->migrator->exists('general.socials')) {
            $this->migrator->add('general.socials', []);
        }

        $this->migrator->deleteIfExists('general.home_page_description');
    }
};
