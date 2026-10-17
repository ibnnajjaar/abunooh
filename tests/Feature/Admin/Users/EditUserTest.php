<?php

use App\Models\User;

use function Pest\Livewire\livewire;

use Illuminate\Support\Facades\Hash;
use Database\Seeders\PermissionsSeeder;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Users\Pages\EditUser;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

beforeEach(function () {
    // TASK-3: UserPolicy::delete checks `user.delete`, which PermissionsSeeder never creates,
    // so the edit page crashes while rendering its DeleteAction. Create it until the policy is fixed.
    $this->seed(PermissionsSeeder::class);
    Permission::findOrCreate('user.delete', 'web_admin');
});

it('cannot load the user edit page without update permission', function () {
    $this->actingAsAdmin(['user.view']);

    $user = User::factory()->create();

    livewire(EditUser::class, ['record' => $user->getKey()])
        ->assertForbidden();
})->group('users', 'users.update');

it('can load the user edit page with update permission', function () {
    $this->actingAsAdmin(['user.view', 'user.update']);

    $user = User::factory()->create();

    livewire(EditUser::class, ['record' => $user->getKey()])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
})->group('users', 'users.update');

it('can update a user through the form', function () {
    $this->actingAsAdmin(['user.view', 'user.update']);

    $user = User::factory()->unverified()->create();

    livewire(EditUser::class, ['record' => $user->getKey()])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'email_verified_at' => '2024-01-01 10:00:00',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);

    expect($user->refresh()->email_verified_at->toDateTimeString())->toBe('2024-01-01 10:00:00');
})->group('users', 'users.update');

it('does not overwrite an existing email verification date', function () {
    $this->actingAsAdmin(['user.view', 'user.update']);

    $user = User::factory()->create(['email_verified_at' => '2020-01-01 00:00:00']);

    livewire(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['email_verified_at' => '2024-01-01 10:00:00'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh()->email_verified_at->toDateTimeString())->toBe('2020-01-01 00:00:00');
})->group('users', 'users.update');

it('validates user edit form data', function () {
    $this->actingAsAdmin(['user.view', 'user.update']);

    $user = User::factory()->create();

    livewire(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['name' => '', 'email' => 'not-an-email'])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required', 'email' => 'email']);
})->group('users', 'users.update');

it('can change the password when admin password login is enabled', function () {
    config(['auth.allow_admin_password_login' => true]);
    $this->actingAsAdmin(['user.view', 'user.update']);

    $user = User::factory()->create();

    livewire(EditUser::class, ['record' => $user->getKey()])
        ->fillForm([
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Hash::check('new-secret-password', $user->refresh()->password))->toBeTrue();
})->group('users', 'users.update');

it('requires the password confirmation when a password is given', function () {
    config(['auth.allow_admin_password_login' => true]);
    $this->actingAsAdmin(['user.view', 'user.update']);

    $user = User::factory()->create();

    livewire(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['password' => 'new-secret-password', 'password_confirmation' => ''])
        ->call('save')
        ->assertHasFormErrors(['password_confirmation' => 'required_with']);
})->group('users', 'users.update');

it('sets requires_password_update from the form checkbox')
    ->todo(note: 'TASK-4: the form field require_password_update_on_next_login is never read by UserPasswordData')
    ->group('users', 'users.update');

it('renders the edit page without a manually created user.delete permission')
    ->todo(note: 'TASK-3: the edit page throws PermissionDoesNotExist for user.delete')
    ->group('users', 'users.update');
