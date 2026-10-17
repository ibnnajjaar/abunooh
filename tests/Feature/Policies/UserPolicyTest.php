<?php

use App\Models\User;
use App\Policies\UserPolicy;
use Database\Seeders\PermissionsSeeder;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);

    // Permissions live on the admin guard, as they do inside the panel.
    auth()->shouldUse('web_admin');
});

it('grants each ability only with its permission', function (string $ability, string $permission, bool $needsModel) {
    $policy = new UserPolicy();
    $user = User::factory()->create();
    $args = $needsModel ? [$user, User::factory()->make()] : [$user];

    expect($policy->{$ability}(...$args))->toBeFalse();

    $user->givePermissionTo($permission);

    expect($policy->{$ability}(...$args))->toBeTrue();
})->with([
    ['viewAny', 'user.view', false],
    ['view', 'user.view', true],
    ['create', 'user.create', false],
    ['update', 'user.update', true],
    ['deleteAny', 'user.delete.any', false],
    ['forceDeleteAny', 'user.force_delete.any', false],
]);

it('authorizes delete, restore and force delete with seeded permissions')
    ->todo(note: 'TASK-3: delete, restore and forceDelete check user.delete / user.force_delete, which are not seeded');
