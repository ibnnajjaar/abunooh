<?php

use function Pest\Livewire\livewire;

use App\Filament\Admin\Pages\Settings;
use App\Support\Settings\SiteSettings;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('fills the form with the current settings', function () {
    $this->actingAsAdmin(['settings.view', 'settings.update']);

    $settings = app(SiteSettings::class);
    $settings->site_name = 'Current Name';
    $settings->site_description = 'Current Description';
    $settings->home_page_title = 'Current Title';
    $settings->socials = [['name' => 'X', 'link' => 'https://x.com/me']];
    $settings->save();

    $component = livewire(Settings::class)
        ->assertOk()
        ->assertSchemaStateSet([
            'site_name' => 'Current Name',
            'site_description' => 'Current Description',
        ]);

    expect(array_values($component->get('data.socials')))->toBe([['name' => 'X', 'link' => 'https://x.com/me']]);
})->group('settings');

it('can update site settings', function () {
    $this->actingAsAdmin(['settings.update']);

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
        ->assertNotified('Settings saved successfully!');

    $settings = app(SiteSettings::class);
    expect($settings->site_name)->toBe('New Site Name')
        ->and($settings->home_page_title)->toBe('<p>New Hero Title</p>')
        ->and($settings->site_description)->toBe('New Site Description')
        ->and(array_values($settings->socials))->toBe([
            ['name' => 'Github', 'link' => 'https://github.com/ibnnajjaar'],
        ]);
})->group('settings');

it('validates site settings', function () {
    $this->actingAsAdmin(['settings.update']);

    livewire(Settings::class)
        ->fillForm([
            'site_name' => '',
            'home_page_title' => '',
            'site_description' => '',
        ])
        ->call('save')
        ->assertHasFormErrors([
            'site_name' => 'required',
            'site_description' => 'required',
        ]);
})->group('settings');

it('validates social links', function () {
    $this->actingAsAdmin(['settings.update']);

    $component = livewire(Settings::class)
        ->fillForm([
            'site_name' => 'Name',
            'home_page_title' => 'Title',
            'site_description' => 'Description',
            'socials' => [
                ['name' => '', 'link' => 'not-a-url'],
            ],
        ])
        ->call('save');

    $key = array_key_first($component->get('data.socials'));

    $component->assertHasFormErrors([
        "socials.{$key}.name" => 'required',
        "socials.{$key}.link" => 'url',
    ]);
})->group('settings');

it('shows settings read only without update permission', function () {
    $this->actingAsAdmin(['settings.view']);

    livewire(Settings::class)
        ->assertOk()
        ->assertFormFieldDisabled('site_name')
        ->assertFormFieldDisabled('site_description')
        ->assertFormFieldDisabled('home_page_title')
        ->assertFormFieldDisabled('socials')
        ->assertDontSee('Save Settings');
})->group('settings');

it('does not save settings without update permission', function () {
    $this->actingAsAdmin(['settings.view']);

    $original = app(SiteSettings::class)->site_name;

    livewire(Settings::class)
        ->set('data.site_name', 'Hacked Name')
        ->call('save')
        ->assertNotified('You do not have permission to edit settings.');

    expect(app(SiteSettings::class)->refresh()->site_name)->toBe($original);
})->group('settings');
