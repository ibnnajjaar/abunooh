<?php

use App\Support\Enums\UserStatus;

it('provides labels and options for every case', function () {
    expect(UserStatus::labels())->toBe([
        'active' => 'Active',
        'inactive' => 'Inactive',
    ])
        ->and(UserStatus::options())->toBe(UserStatus::labels())
        ->and(UserStatus::getKeys())->toBe(['active', 'inactive']);
});

it('returns the label and color of a case', function () {
    expect(UserStatus::Active->getLabel())->toBe('Active')
        ->and(UserStatus::Inactive->getLabel())->toBe('Inactive')
        ->and(UserStatus::Active->getColor())->toBe('success')
        ->and(UserStatus::Inactive->getColor())->toBe('danger');
});

it('knows whether the status is active', function () {
    expect(UserStatus::Active->isActive())->toBeTrue()
        ->and(UserStatus::Inactive->isActive())->toBeFalse();
});
