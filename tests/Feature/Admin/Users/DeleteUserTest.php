<?php

use App\Models\User;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

use Filament\Actions\DeleteBulkAction;
use Database\Seeders\PermissionsSeeder;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

beforeEach(function () {
    // TASK-3: UserPolicy::delete checks `user.delete`, which PermissionsSeeder never creates,
    // so the edit page crashes while rendering its DeleteAction. Create it until the policy is fixed.
    $this->seed(PermissionsSeeder::class);
    Permission::findOrCreate('user.delete', 'web_admin');
});

it('can bulk delete users with delete any permission', function () {
    $this->actingAsAdmin(['user.view', 'user.delete.any']);

    $users = User::factory(2)->create();

    livewire(ListUsers::class)
        ->callTableBulkAction(DeleteBulkAction::class, $users)
        ->assertNotified();

    foreach ($users as $user) {
        assertDatabaseMissing('users', ['id' => $user->id]);
    }
})->group('users', 'users.delete');

it('hides the bulk delete action without delete any permission', function () {
    $this->actingAsAdmin(['user.view']);

    $users = User::factory(2)->create();

    livewire(ListUsers::class)
        ->assertTableBulkActionHidden(DeleteBulkAction::class);

    foreach ($users as $user) {
        assertDatabaseHas('users', ['id' => $user->id]);
    }
})->group('users', 'users.delete');

it('can delete a user from the edit page with delete permission', function () {
    $this->actingAsAdmin(['user.view', 'user.update', 'user.delete']);

    $user = User::factory()->create();

    livewire(EditUser::class, ['record' => $user->getKey()])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseMissing('users', ['id' => $user->id]);
})->group('users', 'users.delete');

it('hides the delete action on the edit page without delete permission', function () {
    $this->actingAsAdmin(['user.view', 'user.update']);

    $user = User::factory()->create();

    livewire(EditUser::class, ['record' => $user->getKey()])
        ->assertActionHidden(DeleteAction::class);
})->group('users', 'users.delete');
