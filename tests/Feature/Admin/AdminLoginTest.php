<?php

use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

use Database\Seeders\PermissionsSeeder;
use App\Filament\Admin\Pages\Auth\AdminLogin;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('shows the google sign in button on the login page', function () {
    get(route('filament.admin.auth.login'))
        ->assertOk()
        ->assertSee('Sign in with Google')
        ->assertSee(route('admin.login.provider', ['provider' => 'google']), escape: false);
})->group('auth');

it('hides the password form when admin password login is disabled', function () {
    config(['auth.allow_admin_password_login' => false]);

    get(route('filament.admin.auth.login'))
        ->assertOk()
        ->assertDontSee('data.email', escape: false)
        ->assertDontSee('data.password', escape: false);
})->group('auth');

it('shows the password form when admin password login is enabled', function () {
    config(['auth.allow_admin_password_login' => true]);

    get(route('filament.admin.auth.login'))
        ->assertOk()
        ->assertSee('data.email', escape: false)
        ->assertSee('data.password', escape: false);
})->group('auth');

it('can log in with a password when admin password login is enabled', function () {
    config(['auth.allow_admin_password_login' => true]);
    $this->seed(PermissionsSeeder::class);

    $user = User::factory()->create(['password' => 'secret-password']);

    livewire(AdminLogin::class)
        ->fillForm(['email' => $user->email, 'password' => 'secret-password'])
        ->call('authenticate')
        ->assertHasNoFormErrors()
        ->assertRedirect();

    expect(auth('web_admin')->id())->toBe($user->id);
})->group('auth');
