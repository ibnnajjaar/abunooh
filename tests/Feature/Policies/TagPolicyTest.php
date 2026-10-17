<?php

use App\Models\Tag;
use App\Models\User;
use App\Policies\TagPolicy;
use Database\Seeders\PermissionsSeeder;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);

    // Permissions live on the admin guard, as they do inside the panel.
    auth()->shouldUse('web_admin');
});

it('grants each ability only with its permission', function (string $ability, string $permission, bool $needsModel) {
    $policy = new TagPolicy();
    $user = User::factory()->create();
    $args = $needsModel ? [$user, new Tag()] : [$user];

    expect($policy->{$ability}(...$args))->toBeFalse();

    $user->givePermissionTo($permission);

    expect($policy->{$ability}(...$args))->toBeTrue();
})->with([
    ['viewAny', 'tags.view', false],
    ['view', 'tags.view', true],
    ['create', 'tags.create', false],
    ['update', 'tags.update', true],
    ['delete', 'tags.delete', true],
]);
