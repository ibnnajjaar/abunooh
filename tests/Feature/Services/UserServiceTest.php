<?php

use App\Models\User;
use App\DataObjects\UserData;
use App\Services\UserService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\DataObjects\UserPasswordData;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('creates a user', function () {
    $user = (new UserService())->create(
        new UserData('Jane', 'jane@example.com', Carbon::parse('2024-01-01 10:00:00')),
        new UserPasswordData('secret-password', 'secret-password', requires_password_update: true),
    );

    expect($user->exists)->toBeTrue()
        ->and($user->name)->toBe('Jane')
        ->and($user->email)->toBe('jane@example.com')
        ->and($user->email_verified_at->toDateTimeString())->toBe('2024-01-01 10:00:00')
        ->and(Hash::check('secret-password', $user->password))->toBeTrue()
        ->and($user->refresh()->requires_password_update)->toBeTruthy();
});

it('creates a user without a password', function () {
    $user = (new UserService())->create(
        new UserData('Jane', 'jane@example.com', null),
        new UserPasswordData(null, null),
    );

    expect($user->exists)->toBeTrue()
        ->and($user->password)->toBeNull();
});

it('updates a user and sets the verification date when unverified', function () {
    $user = User::factory()->unverified()->create();

    (new UserService())->update($user, new UserData('New Name', 'new@example.com', Carbon::parse('2024-01-01 10:00:00')));

    $user->refresh();

    expect($user->name)->toBe('New Name')
        ->and($user->email)->toBe('new@example.com')
        ->and($user->email_verified_at->toDateTimeString())->toBe('2024-01-01 10:00:00');
});

it('does not overwrite an existing verification date', function () {
    $user = User::factory()->create(['email_verified_at' => '2020-01-01 00:00:00']);

    (new UserService())->update($user, new UserData($user->name, $user->email, Carbon::parse('2024-01-01 10:00:00')));

    expect($user->refresh()->email_verified_at->toDateTimeString())->toBe('2020-01-01 00:00:00');
});

it('ignores an empty password', function () {
    $user = User::factory()->create(['password' => 'original-password']);

    (new UserService())->updatePassword($user, new UserPasswordData(null, null, requires_password_update: true));

    $user->refresh();

    expect(Hash::check('original-password', $user->password))->toBeTrue()
        ->and($user->requires_password_update)->toBeFalsy();
});

it('updates and saves the password', function () {
    $user = User::factory()->create();

    (new UserService())->updatePassword($user, new UserPasswordData('new-password', 'new-password', requires_password_update: true));

    $user->refresh();

    expect(Hash::check('new-password', $user->password))->toBeTrue()
        ->and($user->requires_password_update)->toBeTruthy();
});

it('does not save the password when asked not to', function () {
    $user = User::factory()->create(['password' => 'original-password']);

    (new UserService())->updatePassword($user, new UserPasswordData('new-password', 'new-password'), save: false);

    expect($user->isDirty('password'))->toBeTrue()
        ->and(Hash::check('original-password', $user->refresh()->password))->toBeTrue();
});

it('sends the password notification only when requested', function (bool $emailPassword) {
    $user = Mockery::mock(User::factory()->create())->makePartial();

    $user->shouldReceive('sendUpdatedPasswordNotification')
        ->times($emailPassword ? 1 : 0)
        ->with('new-password');

    (new UserService())->updatePassword($user, new UserPasswordData('new-password', 'new-password', email_password: $emailPassword));
})->with([true, false]);
