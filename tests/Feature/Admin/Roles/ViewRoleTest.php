<?php

use App\Models\Role;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Roles\Pages\ViewRole;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot access view page without view permission')
    ->todo(note: 'TASK-2: RoleResource has no policy, so the view page is open to any admin')
    ->group('roles', 'roles.view');

it('can load the role view page', function () {
    $this->actingAsAdmin(['role.view']);

    $role = Role::create(['name' => 'Editor', 'guard_name' => 'web_admin']);

    livewire(ViewRole::class, ['record' => $role->getKey()])
        ->assertOk()
        ->assertActionVisible('edit');
})->group('roles', 'roles.view');
