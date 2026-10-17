<?php

use App\Models\User;
use App\Support\Enums\UserStatus;

use function Pest\Livewire\livewire;
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
