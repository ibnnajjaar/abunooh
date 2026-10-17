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

it('authorizes deleting any user with the any permission', function (string $ability, string $permission) {
    $policy = new UserPolicy();
    $user = User::factory()->create();
    $other = User::factory()->create();

    expect($policy->{$ability}($user, $other))->toBeFalse();

    $user->givePermissionTo($permission);

    expect($policy->{$ability}($user, $other))->toBeTrue()
        ->and($policy->{$ability}($user, $user))->toBeTrue();
})->with([
    ['delete', 'user.delete.any'],
    ['restore', 'user.force_delete.any'],
    ['forceDelete', 'user.force_delete.any'],
]);

it('authorizes deleting only their own account with the own permission', function (string $ability, string $permission) {
    $policy = new UserPolicy();
    $user = User::factory()->create();
    $other = User::factory()->create();

    $user->givePermissionTo($permission);

    expect($policy->{$ability}($user, $user))->toBeTrue()
        ->and($policy->{$ability}($user, $other))->toBeFalse();
})->with([
    ['delete', 'user.delete.own'],
    ['restore', 'user.force_delete.own'],
    ['forceDelete', 'user.force_delete.own'],
]);
