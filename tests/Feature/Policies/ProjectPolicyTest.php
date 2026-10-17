<?php

use App\Models\User;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use Database\Seeders\PermissionsSeeder;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);

    // Permissions live on the admin guard, as they do inside the panel.
    auth()->shouldUse('web_admin');
});

it('grants each ability only with its permission', function (string $ability, string $permission, bool $needsModel) {
    $policy = new ProjectPolicy();
    $user = User::factory()->create();
    $args = $needsModel ? [$user, new Project()] : [$user];

    expect($policy->{$ability}(...$args))->toBeFalse();

    $user->givePermissionTo($permission);

    expect($policy->{$ability}(...$args))->toBeTrue();
})->with([
    ['viewAny', 'projects.view', false],
    ['view', 'projects.view', true],
    ['create', 'projects.create', false],
    ['update', 'projects.update', true],
    ['delete', 'projects.delete', true],
    ['deleteAny', 'projects.delete', false],
]);
