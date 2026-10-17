<?php

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('records the request ip when created', function () {
    app()->instance('request', Request::create('/', server: ['REMOTE_ADDR' => '203.0.113.7']));

    $activity = Activity::create(['log_name' => 'default', 'description' => 'created']);

    expect($activity->ip)->toBe('203.0.113.7');
});

it('formats the created at date', function () {
    $this->travelTo('2024-05-01 12:00:00');

    $activity = Activity::create(['log_name' => 'default', 'description' => 'created']);

    expect($activity->formatted_created_at)->toBe('0 seconds ago (1 May 2024 @ 12:00)');
});

it('falls back to the log id without a created at date', function () {
    $activity = new Activity();
    $activity->id = 42;

    expect($activity->formatted_created_at)->toBe('Log #42');
});

it('formats subject and causer meta', function () {
    $user = User::factory()->create();

    $activity = Activity::create([
        'log_name' => 'default',
        'description' => 'updated',
        'subject_type' => 'user',
        'subject_id' => $user->id,
        'causer_type' => 'user',
        'causer_id' => $user->id,
    ]);

    expect($activity->formatted_subject_meta)->toBe("[user: #{$user->id}]")
        ->and($activity->formatted_causer_meta)->toBe("[user: #{$user->id}]");
});
