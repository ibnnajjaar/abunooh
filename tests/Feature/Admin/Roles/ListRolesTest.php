<?php

use App\Models\Role;
use App\Models\User;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Roles\Pages\ListRoles;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot view the list roles page without permissions')
    ->todo(note: 'TASK-2: RoleResource has no policy, so the list page is open to any admin')
    ->group('roles');

it('can view the list roles page', function () {
    $this->actingAsAdmin(['role.view']);

    $roles = collect([
        Role::create(['name' => 'Admin', 'guard_name' => 'web_admin']),
        Role::create(['name' => 'Editor', 'guard_name' => 'web_admin']),
    ]);

    livewire(ListRoles::class)
        ->assertOk()
        ->assertCanSeeTableRecords($roles);
})->group('roles');

it('shows the number of users per role', function () {
    $this->actingAsAdmin(['role.view']);

    $role = Role::create(['name' => 'Editor', 'guard_name' => 'web_admin']);
    User::factory(2)->create()->each->assignRole($role);

    livewire(ListRoles::class)
        ->assertTableColumnStateSet('users_count', 2, $role);
})->group('roles');

it('shows the create action on list page', function () {
    $this->actingAsAdmin(['role.view', 'role.create']);

    livewire(ListRoles::class)
        ->assertActionVisible('create');
})->group('roles');
