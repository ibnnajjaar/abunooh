<?php

namespace IbnNajjaar\Helpers;

use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once __DIR__ . '/helpers.php';
    }
}
