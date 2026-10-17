<?php

use App\DataObjects\UserPasswordData;

it('creates password data from an array', function () {
    $data = UserPasswordData::fromArray([
        'password' => 'secret',
        'password_confirmation' => 'secret',
        'requires_password_update' => true,
        'email_password' => true,
    ]);

    expect($data->password)->toBe('secret')
        ->and($data->password_confirmation)->toBe('secret')
        ->and($data->requires_password_update)->toBeTrue()
        ->and($data->email_password)->toBeTrue();
});

it('defaults the flags to false', function () {
    $data = UserPasswordData::fromArray([
        'requires_password_update' => null,
        'email_password' => null,
    ]);

    expect($data->password)->toBeNull()
        ->and($data->password_confirmation)->toBeNull()
        ->and($data->requires_password_update)->toBeFalse()
        ->and($data->email_password)->toBeFalse();
});

it('converts to an array including null values', function () {
    expect((new UserPasswordData(null, null))->toArray())->toBe([
        'password' => null,
        'password_confirmation' => null,
        'requires_password_update' => false,
        'email_password' => false,
    ]);
});
