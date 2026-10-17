<?php

use App\Models\Activity;
use App\Livewire\ChangeLogList;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('lists changed attributes with old and new values', function () {
    $this->actingAsAdmin();

    $activity = Activity::create([
        'log_name' => 'default',
        'description' => 'updated',
        'properties' => [
            'attributes' => ['name' => 'New Name', 'email' => 'new@example.com'],
            'old' => ['name' => 'Old Name'],
        ],
    ]);

    livewire(ChangeLogList::class, ['record' => $activity])
        ->assertOk()
        ->assertSee('Change Log')
        ->assertSeeInOrder(['name', 'Old Name', 'New Name'])
        ->assertSee('new@example.com');
});

it('renders an empty change log without attributes', function () {
    $this->actingAsAdmin();

    $activity = Activity::create([
        'log_name' => 'default',
        'description' => 'deleted',
        'properties' => [],
    ]);

    livewire(ChangeLogList::class, ['record' => $activity])
        ->assertOk()
        ->assertSee('Change Log');
});
