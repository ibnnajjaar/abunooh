<?php

use Illuminate\Http\Request;
use App\Support\Settings\SiteSettings;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('formats minutes for display', function (int $minutes, bool $alwaysHours, string $expected) {
    expect(format_minutes_for_display($minutes, $alwaysHours))->toBe($expected);
})->with([
    'zero' => [0, false, '—'],
    'minutes only' => [45, false, '45 minutes'],
    'minutes with forced hours' => [45, true, '00 hours 45 minutes'],
    'exact hours' => [120, false, '02 hours 00 minutes'],
    'hours and minutes' => [135, false, '02 hours 15 minutes'],
]);

it('formats minutes as hour and minute', function (int $minutes, string $expected) {
    expect(format_minutes_as_hour_minute($minutes))->toBe($expected);
})->with([
    'zero' => [0, '—'],
    'minutes only' => [5, '00:05'],
    'hours and minutes' => [605, '10:05'],
]);

it('returns the request ip', function () {
    config(['app.cloudflare_enabled' => false]);
    $_SERVER['HTTP_CF_CONNECTING_IP'] = '1.1.1.1';

    app()->instance('request', Request::create('/', server: ['REMOTE_ADDR' => '10.0.0.5']));

    expect(get_request_ip())->toBe('10.0.0.5');

    unset($_SERVER['HTTP_CF_CONNECTING_IP']);
});

it('returns the cloudflare ip when cloudflare is enabled', function () {
    config(['app.cloudflare_enabled' => true]);
    $_SERVER['HTTP_CF_CONNECTING_IP'] = '1.1.1.1';

    app()->instance('request', Request::create('/', server: ['REMOTE_ADDR' => '10.0.0.5']));

    expect(get_request_ip())->toBe('1.1.1.1');

    unset($_SERVER['HTTP_CF_CONNECTING_IP']);
});

it('falls back to the request ip without the cloudflare header', function () {
    config(['app.cloudflare_enabled' => true]);
    unset($_SERVER['HTTP_CF_CONNECTING_IP']);

    app()->instance('request', Request::create('/', server: ['REMOTE_ADDR' => '10.0.0.5']));

    expect(get_request_ip())->toBe('10.0.0.5');
});

it('reads site settings', function () {
    $settings = app(SiteSettings::class);
    $settings->site_name = 'My Site';
    $settings->save();

    expect(get_setting('site_name'))->toBe('My Site')
        ->and(get_setting('missing_setting', 'fallback'))->toBe('fallback');
});
