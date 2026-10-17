<?php

use App\Models\User;
use App\Models\Activity;
use App\Livewire\ChangeLogList;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Activities\Pages\ViewActivity;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot view an activity without permissions', function () {
    $this->actingAsAdmin();

    $activity = Activity::create(['log_name' => 'default', 'description' => 'created']);

    livewire(ViewActivity::class, ['record' => $activity->getKey()])
        ->assertForbidden();
})->group('activities', 'activities.view');

it('shows activity details in view page', function () {
    $admin = $this->actingAsAdmin(['activity.view']);

    $subject = User::factory()->create(['name' => 'Subject User']);
    $activity = Activity::create([
        'log_name' => 'default',
        'description' => 'updated',
        'causer_type' => 'user',
        'causer_id' => $admin->id,
        'subject_type' => 'user',
        'subject_id' => $subject->id,
        'properties' => ['attributes' => ['name' => 'Subject User'], 'old' => ['name' => 'Old Name']],
    ]);

    livewire(ViewActivity::class, ['record' => $activity->getKey()])
        ->assertOk()
        ->assertSchemaStateSet([
            'description' => 'updated',
            'causer.name' => $admin->name,
            'subject.name' => 'Subject User',
        ])
        ->assertSeeLivewire(ChangeLogList::class);
})->group('activities', 'activities.view');

it('does not show an edit action for activities', function () {
    $this->actingAsAdmin(['activity.view']);

    $activity = Activity::create(['log_name' => 'default', 'description' => 'created']);

    livewire(ViewActivity::class, ['record' => $activity->getKey()])
        ->assertOk()
        ->assertActionDoesNotExist('edit');
})->group('activities', 'activities.view');
