<?php

if (! function_exists('get_request_ip')) {
    /**
     * Get the ip from the current request
     */
    function get_request_ip(): ?string
    {
        if (config('app.cloudflare_enabled') && isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            return request()->ip();
        }
    }
}

if (! function_exists('get_setting')) {

    function get_setting(string $key, $default = null)
    {
        return app(\App\Support\Settings\SiteSettings::class)->{$key} ?? $default;
    }
}

if (! function_exists('format_minutes_for_display')) {

    function format_minutes_for_display(int $minutes, bool $always_display_hours = false): string
    {
        if (! $minutes) {
            return '—';
        }

        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;

        if ($hours < 1 && ! $always_display_hours) {
            return sprintf('%02d minutes', $minutes);
        }

        return sprintf('%02d hours %02d minutes', $hours, $minutes);
    }
}

if (! function_exists('format_minutes_as_hour_minute')) {

    function format_minutes_as_hour_minute(int $minutes, bool $always_display_hours = false): string
    {
        if (! $minutes) {
            return '—';
        }

        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
