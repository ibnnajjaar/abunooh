<?php

use App\Models\User;
use App\Models\Activity;
use Filament\Facades\Filament;
use App\Support\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('is active by default', function () {
    $user = User::factory()->create();

    expect($user->status)->toBe(UserStatus::Active)
        ->and($user->canAccessAdminPanel())->toBeTrue()
        ->and($user->canAccessPanel(Filament::getPanel('admin')))->toBeTrue();
});

it('cannot access the panel when inactive', function () {
    $user = User::factory()->create();
    $user->forceFill(['status' => UserStatus::Inactive])->save();

    expect($user->canAccessAdminPanel())->toBeFalse()
        ->and($user->canAccessPanel(Filament::getPanel('admin')))->toBeFalse();
});

it('hashes the password and hides secrets when serialized', function () {
    $user = User::factory()->create(['password' => 'plain-password']);

    expect(Hash::check('plain-password', $user->password))->toBeTrue()
        ->and($user->toArray())->not->toHaveKeys(['password', 'remember_token']);
});

it('links to its admin page', function () {
    $user = User::factory()->create(['name' => 'Jane']);

    expect($user->admin_url)->toEndWith("/admin/users/{$user->id}")
        ->and($user->admin_url_label)->toBe('Jane');
});

it('logs changes without hidden attributes', function () {
    $user = User::factory()->create(['name' => 'Before']);

    $user->update(['name' => 'After', 'password' => 'new-password']);

    $activity = Activity::query()
        ->where('subject_type', 'user')
        ->where('subject_id', $user->id)
        ->where('description', 'updated')
        ->latest('id')
        ->first();

    expect($activity)->not->toBeNull()
        ->and($activity->properties['attributes'])->toHaveKey('name', 'After')
        ->and($activity->properties['attributes'])->not->toHaveKey('password')
        ->and($activity->properties['old'])->toHaveKey('name', 'Before');
});
