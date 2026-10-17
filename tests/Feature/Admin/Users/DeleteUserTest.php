<?php

use App\Models\User;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

use Filament\Actions\DeleteBulkAction;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

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
    $this->actingAsAdmin(['user.view', 'user.update', 'user.delete.any']);

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

it('can only delete their own account with the delete own permission', function () {
    $admin = $this->actingAsAdmin(['user.view', 'user.update', 'user.delete.own']);

    $other = User::factory()->create();

    livewire(EditUser::class, ['record' => $other->getKey()])
        ->assertActionHidden(DeleteAction::class);

    livewire(EditUser::class, ['record' => $admin->getKey()])
        ->assertActionVisible(DeleteAction::class);
})->group('users', 'users.delete');
