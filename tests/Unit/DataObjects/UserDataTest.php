<?php

use App\DataObjects\UserData;
use Illuminate\Support\Carbon;

it('creates user data from an array', function () {
    $data = UserData::fromArray([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'email_verified_at' => '2024-01-01 10:00:00',
    ]);

    expect($data->name)->toBe('Jane Doe')
        ->and($data->email)->toBe('jane@example.com')
        ->and($data->email_verified_at)->toBeInstanceOf(Carbon::class)
        ->and($data->email_verified_at->toDateTimeString())->toBe('2024-01-01 10:00:00');
});

it('leaves the verification date empty when not given', function () {
    $data = UserData::fromArray(['name' => 'Jane', 'email' => 'jane@example.com']);

    expect($data->email_verified_at)->toBeNull();
});

it('converts to an array', function () {
    $data = new UserData('Jane', 'jane@example.com', null);

    expect($data->toArray())->toBe([
        'name' => 'Jane',
        'email' => 'jane@example.com',
        'email_verified_at' => null,
    ]);
});
