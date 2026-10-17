<?php

use App\Models\User;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Users\Pages\ViewUser;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot access view page without view permission', function () {
    $this->actingAsAdmin();

    $user = User::factory()->create();

    livewire(ViewUser::class, ['record' => $user->getKey()])
        ->assertForbidden();
})->group('users', 'users.view');

it('shows user details in view page', function () {
    $this->actingAsAdmin(['user.view']);

    $user = User::factory()->create();

    livewire(ViewUser::class, ['record' => $user->getKey()])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
})->group('users', 'users.view');
