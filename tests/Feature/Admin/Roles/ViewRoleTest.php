<?php

use App\Models\Role;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Roles\Pages\ViewRole;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot access view page without view permission', function () {
    $this->actingAsAdmin();

    $role = Role::create(['name' => 'Editor', 'guard_name' => 'web_admin']);

    livewire(ViewRole::class, ['record' => $role->getKey()])
        ->assertForbidden();
})->group('roles', 'roles.view');

it('can load the role view page', function () {
    $this->actingAsAdmin(['role.view']);

    $role = Role::create(['name' => 'Editor', 'guard_name' => 'web_admin']);

    livewire(ViewRole::class, ['record' => $role->getKey()])
        ->assertOk()
        ->assertActionHidden('edit');

    $this->actingAsAdmin(['role.view', 'role.update']);

    livewire(ViewRole::class, ['record' => $role->getKey()])
        ->assertActionVisible('edit');
})->group('roles', 'roles.view');
