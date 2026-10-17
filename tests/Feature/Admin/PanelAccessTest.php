<?php

use App\Models\User;

use function Pest\Laravel\get;

use App\Support\Enums\UserStatus;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('redirects guests to the admin login page', function () {
    get('/admin')
        ->assertRedirect(route('filament.admin.auth.login'));
})->group('panel');

it('lets active users reach the dashboard', function () {
    $this->actingAsAdmin();

    get(route('filament.admin.pages.dashboard'))
        ->assertOk();
})->group('panel');

it('denies inactive users access to the panel', function () {
    $user = User::factory()->create();
    $user->forceFill(['status' => UserStatus::Inactive])->save();

    $this->actingAsAdmin(user: $user);

    get(route('filament.admin.pages.dashboard'))
        ->assertForbidden();
})->group('panel');
