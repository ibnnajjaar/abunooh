<?php

use App\Models\User;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Users\Pages\ListUsers;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot view the list users page without permissions', function () {
    $this->actingAsAdmin();

    livewire(ListUsers::class)
        ->assertForbidden();
})->group('users');

it('can view the list users page with permissions', function () {
    $admin = $this->actingAsAdmin(['user.view']);

    $users = User::factory(3)->create();

    livewire(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($users->push($admin));
})->group('users');

it('can search users by name and email', function () {
    $this->actingAsAdmin(['user.view']);

    $alice = User::factory()->create(['name' => 'Alice Example', 'email' => 'alice@example.com']);
    $bob = User::factory()->create(['name' => 'Bob Example', 'email' => 'bob@sample.org']);

    livewire(ListUsers::class)
        ->searchTable('Alice')
        ->assertCanSeeTableRecords([$alice])
        ->assertCanNotSeeTableRecords([$bob])
        ->searchTable('sample.org')
        ->assertCanSeeTableRecords([$bob])
        ->assertCanNotSeeTableRecords([$alice]);
})->group('users');

it('shows the create action only with create permission', function () {
    $this->actingAsAdmin(['user.view']);

    livewire(ListUsers::class)
        ->assertActionHidden('create');

    $this->actingAsAdmin(['user.view', 'user.create']);

    livewire(ListUsers::class)
        ->assertActionVisible('create');
})->group('users');
