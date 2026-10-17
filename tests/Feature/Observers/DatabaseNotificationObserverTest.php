<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\DatabaseNotification;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('logs when a database notification is saved and deleted', function () {
    $user = User::factory()->create();
    $id = (string) Str::uuid();

    Log::spy();

    $notification = DatabaseNotification::create([
        'id' => $id,
        'type' => 'test',
        'notifiable_type' => $user->getMorphClass(),
        'notifiable_id' => $user->id,
        'data' => [],
    ]);

    $notification->delete();

    Log::shouldHaveReceived('info')->with("Saving notification: {$id}")->once();
    Log::shouldHaveReceived('info')->with("Deleting notification: {$id}")->once();
    Log::shouldHaveReceived('info')->with("Deleted notification: {$id}")->once();
});
