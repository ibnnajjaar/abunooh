<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Activity;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Activities\Pages\ListActivities;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

function logActivity(User $causer, $subject, string $description = 'updated', array $attributes = []): Activity
{
    return Activity::create(array_merge([
        'log_name' => 'default',
        'description' => $description,
        'causer_type' => $causer->getMorphClass(),
        'causer_id' => $causer->getKey(),
        'subject_type' => $subject->getMorphClass(),
        'subject_id' => $subject->getKey(),
        'properties' => [],
    ], $attributes));
}

it('cannot view the activity log without permissions')
    ->todo(note: 'TASK-2: ActivityResource has no policy, so the activity log is open to any admin')
    ->group('activities');

it('can view the activity log', function () {
    $admin = $this->actingAsAdmin();
    Activity::query()->delete();

    $activities = collect([
        logActivity($admin, $admin, 'first'),
        logActivity($admin, $admin, 'second'),
    ]);

    livewire(ListActivities::class)
        ->assertOk()
        ->assertCanSeeTableRecords($activities);
})->group('activities');

it('lists the newest activities first', function () {
    $admin = $this->actingAsAdmin();
    Activity::query()->delete();

    $old = logActivity($admin, $admin, 'old', ['created_at' => now()->subDays(2)]);
    $new = logActivity($admin, $admin, 'new', ['created_at' => now()]);

    livewire(ListActivities::class)
        ->assertCanSeeTableRecords([$new, $old], inOrder: true);
})->group('activities');

it('can search activities by ip address', function () {
    $admin = $this->actingAsAdmin();
    Activity::query()->delete();

    $match = logActivity($admin, $admin);
    $match->forceFill(['ip' => '10.0.0.1'])->save();
    $other = logActivity($admin, $admin);
    $other->forceFill(['ip' => '192.168.1.1'])->save();

    livewire(ListActivities::class)
        ->searchTable('10.0.0.1')
        ->assertCanSeeTableRecords([$match])
        ->assertCanNotSeeTableRecords([$other]);
})->group('activities');

it('can filter activities by subject type and subject id', function () {
    $admin = $this->actingAsAdmin();

    $role = Role::create(['name' => 'Editor', 'guard_name' => 'web_admin']);
    $otherUser = User::factory()->create();
    Activity::query()->delete();

    $onUser = logActivity($admin, $admin);
    $onRole = logActivity($admin, $role);
    $onOtherUser = logActivity($admin, $otherUser);

    livewire(ListActivities::class)
        ->filterTable('subject_type', 'role')
        ->assertCanSeeTableRecords([$onRole])
        ->assertCanNotSeeTableRecords([$onUser, $onOtherUser]);

    livewire(ListActivities::class)
        ->filterTable('subject', ['subject_id' => $otherUser->id])
        ->assertCanSeeTableRecords([$onOtherUser])
        ->assertCanNotSeeTableRecords([$onUser]);
})->group('activities');

it('can filter activities by causer type and causer id', function () {
    $admin = $this->actingAsAdmin();
    $otherAdmin = User::factory()->create();
    Activity::query()->delete();

    $byAdmin = logActivity($admin, $admin);
    $byOther = logActivity($otherAdmin, $admin);

    livewire(ListActivities::class)
        ->filterTable('causer_type', 'user')
        ->assertCanSeeTableRecords([$byAdmin, $byOther]);

    livewire(ListActivities::class)
        ->filterTable('causer', ['causer_id' => $otherAdmin->id])
        ->assertCanSeeTableRecords([$byOther])
        ->assertCanNotSeeTableRecords([$byAdmin]);
})->group('activities');

it('can filter activities by created date range', function () {
    $admin = $this->actingAsAdmin();
    Activity::query()->delete();

    $old = logActivity($admin, $admin, 'old', ['created_at' => '2024-01-01 10:00:00']);
    $mid = logActivity($admin, $admin, 'mid', ['created_at' => '2024-06-15 10:00:00']);
    $new = logActivity($admin, $admin, 'new', ['created_at' => '2025-01-01 10:00:00']);

    livewire(ListActivities::class)
        ->filterTable('created_at', ['created_from' => '2024-06-01', 'created_until' => '2024-12-31'])
        ->assertCanSeeTableRecords([$mid])
        ->assertCanNotSeeTableRecords([$old, $new]);
})->group('activities');
