<?php

use App\Models\Role;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

use Filament\Actions\DeleteBulkAction;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

use App\Filament\Admin\Resources\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Roles\Pages\ListRoles;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('can delete a role from the edit page', function () {
    $this->actingAsAdmin(['role.view', 'role.update', 'role.delete']);

    $role = Role::create(['name' => 'Editor', 'guard_name' => 'web_admin']);

    livewire(EditRole::class, ['record' => $role->getKey()])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseMissing('roles', ['id' => $role->id]);
})->group('roles', 'roles.delete');

it('can bulk delete roles', function () {
    $this->actingAsAdmin(['role.view', 'role.delete']);

    $roles = collect([
        Role::create(['name' => 'One', 'guard_name' => 'web_admin']),
        Role::create(['name' => 'Two', 'guard_name' => 'web_admin']),
    ]);

    livewire(ListRoles::class)
        ->callTableBulkAction(DeleteBulkAction::class, $roles)
        ->assertNotified();

    foreach ($roles as $role) {
        assertDatabaseMissing('roles', ['id' => $role->id]);
    }
})->group('roles', 'roles.delete');

it('cannot delete roles without delete permission', function () {
    $this->actingAsAdmin(['role.view', 'role.update']);

    $role = Role::create(['name' => 'Editor', 'guard_name' => 'web_admin']);

    livewire(EditRole::class, ['record' => $role->getKey()])
        ->assertActionHidden(DeleteAction::class);

    livewire(ListRoles::class)
        ->assertTableBulkActionHidden(DeleteBulkAction::class);

    assertDatabaseHas('roles', ['id' => $role->id]);
})->group('roles', 'roles.delete');
