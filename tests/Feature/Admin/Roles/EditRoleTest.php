<?php

use App\Models\Role;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Permission;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Roles\Pages\EditRole;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot load the role edit page without update permission')
    ->todo(note: 'TASK-2: RoleResource has no policy, so the edit page is open to any admin')
    ->group('roles', 'roles.update');

it('can load the role edit page', function () {
    $this->actingAsAdmin(['role.view', 'role.update']);

    $role = Role::create(['name' => 'Editor', 'description' => 'Edits', 'guard_name' => 'web_admin']);
    $role->givePermissionTo('tags.view');

    livewire(EditRole::class, ['record' => $role->getKey()])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => 'Editor',
            'description' => 'Edits',
            'permissions' => [Permission::findByName('tags.view', 'web_admin')->id],
        ]);
})->group('roles', 'roles.update');

it('can update a role and its permissions', function () {
    $this->actingAsAdmin(['role.view', 'role.update']);

    $role = Role::create(['name' => 'Editor', 'guard_name' => 'web_admin']);
    $role->givePermissionTo('tags.view');

    $postsView = Permission::findByName('posts.view', 'web_admin');

    livewire(EditRole::class, ['record' => $role->getKey()])
        ->fillForm([
            'name' => 'Writer',
            'description' => 'Writes posts',
            'permissions' => [$postsView->id],
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'Writer', 'description' => 'Writes posts']);

    expect($role->refresh()->permissions->pluck('name')->all())->toBe(['posts.view']);
})->group('roles', 'roles.update');

it('validates role edit form data', function () {
    $this->actingAsAdmin(['role.view', 'role.update']);

    $role = Role::create(['name' => 'Editor', 'guard_name' => 'web_admin']);

    livewire(EditRole::class, ['record' => $role->getKey()])
        ->fillForm(['name' => ''])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
})->group('roles', 'roles.update');
