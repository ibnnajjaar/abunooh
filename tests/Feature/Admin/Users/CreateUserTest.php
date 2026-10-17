<?php

use App\Models\User;
use App\Support\Enums\UserStatus;

use function Pest\Livewire\livewire;

use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Users\Pages\CreateUser;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot access create page without create permission', function () {
    $this->actingAsAdmin(['user.view']);

    livewire(CreateUser::class)
        ->assertForbidden();
})->group('users', 'users.create');

it('can access create page with create permission', function () {
    $this->actingAsAdmin(['user.view', 'user.create']);

    livewire(CreateUser::class)
        ->assertOk();
})->group('users', 'users.create');

it('can create a user through the form', function () {
    $this->actingAsAdmin(['user.view', 'user.create']);

    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas('users', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'status' => UserStatus::Active->value,
    ]);
})->group('users', 'users.create');

it('validates user form data', function () {
    $this->actingAsAdmin(['user.view', 'user.create']);

    livewire(CreateUser::class)
        ->fillForm(['name' => '', 'email' => ''])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'email' => 'required']);

    livewire(CreateUser::class)
        ->fillForm(['name' => 'Jane', 'email' => 'not-an-email'])
        ->call('create')
        ->assertHasFormErrors(['email' => 'email']);
})->group('users', 'users.create');

it('requires a unique email', function () {
    $this->actingAsAdmin(['user.view', 'user.create']);

    User::factory()->create(['email' => 'taken@example.com']);

    livewire(CreateUser::class)
        ->fillForm(['name' => 'Jane', 'email' => 'taken@example.com'])
        ->call('create')
        ->assertHasFormErrors(['email' => 'unique']);
})->group('users', 'users.create');

it('creates a user with a password and the password update flag', function () {
    config(['auth.allow_admin_password_login' => true]);
    $this->actingAsAdmin(['user.view', 'user.create']);

    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'email_verified_at' => '2024-01-01 10:00:00',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'requires_password_update' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'jane@example.com')->firstOrFail();

    expect(Hash::check('secret-password', $user->password))->toBeTrue()
        ->and($user->requires_password_update)->toBeTruthy()
        ->and($user->email_verified_at->toDateTimeString())->toBe('2024-01-01 10:00:00');
})->group('users', 'users.create');
