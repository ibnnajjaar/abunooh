<?php

use App\Models\Role;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Permission;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Roles\Pages\CreateRole;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot access create page without create permission')
    ->todo(note: 'TASK-2: RoleResource has no policy, so the create page is open to any admin')
    ->group('roles', 'roles.create');

it('can access create page', function () {
    $this->actingAsAdmin(['role.view', 'role.create']);

    livewire(CreateRole::class)
        ->assertOk();
})->group('roles', 'roles.create');

it('can create a role with permissions through the form', function () {
    $this->actingAsAdmin(['role.view', 'role.create']);

    $permissions = Permission::whereIn('name', ['tags.view', 'tags.create'])->get();

    livewire(CreateRole::class)
        ->fillForm([
            'name' => 'Editor',
            'description' => 'Can edit content',
            'permissions' => $permissions->pluck('id')->all(),
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas('roles', [
        'name' => 'Editor',
        'description' => 'Can edit content',
    ]);

    $role = Role::where('name', 'Editor')->first();

    expect($role->guard_name)->toBe('web_admin')
        ->and($role->permissions->pluck('name')->sort()->values()->all())->toBe(['tags.create', 'tags.view']);
})->group('roles', 'roles.create');

it('validates role form data', function () {
    $this->actingAsAdmin(['role.view', 'role.create']);

    livewire(CreateRole::class)
        ->fillForm(['name' => ''])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
})->group('roles', 'roles.create');
