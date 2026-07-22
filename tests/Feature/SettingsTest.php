<?php

use function Pest\Livewire\livewire;

use App\Filament\Admin\Pages\Settings;
use App\Support\Settings\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can update site settings', function () {
    $this->actingAsAdmin(['edit settings']);

    livewire(Settings::class)
        ->fillForm([
            'site_name' => 'New Site Name',
            'home_page_title' => 'New Hero Title',
            'site_description' => 'New Site Description',
            'socials' => [
                ['name' => 'Github', 'link' => 'https://github.com/ibnnajjaar'],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $settings = app(SiteSettings::class);
    expect($settings->site_name)->toBe('New Site Name')
        ->and($settings->home_page_title)->toBe('New Hero Title')
        ->and($settings->site_description)->toBe('New Site Description')
        ->and($settings->socials)->toBe([
            ['name' => 'Github', 'link' => 'https://github.com/ibnnajjaar'],
        ]);
});
