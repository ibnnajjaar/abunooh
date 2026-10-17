<?php

use App\Support\Enums\PublishStatuses;

it('provides labels and options for every case', function () {
    expect(PublishStatuses::labels())->toBe([
        'draft' => 'Draft',
        'pending' => 'Pending',
        'rejected' => 'Rejected',
        'published' => 'Published',
    ])
        ->and(PublishStatuses::options())->toBe(PublishStatuses::labels())
        ->and(PublishStatuses::getKeys())->toBe(['draft', 'pending', 'rejected', 'published']);
});

it('returns the label of a case', function (PublishStatuses $status, string $label) {
    expect($status->getLabel())->toBe($label);
})->with([
    [PublishStatuses::DRAFT, 'Draft'],
    [PublishStatuses::PENDING, 'Pending'],
    [PublishStatuses::REJECTED, 'Rejected'],
    [PublishStatuses::PUBLISHED, 'Published'],
]);

it('returns the color of a case', function (PublishStatuses $status, string $color) {
    expect($status->getColor())->toBe($color)
        ->and($status->getBadgeColor())->toBe($color)
        ->and(PublishStatuses::getColorForState($status->value))->toBe($color);
})->with([
    [PublishStatuses::DRAFT, 'secondary'],
    [PublishStatuses::PENDING, 'warning'],
    [PublishStatuses::REJECTED, 'danger'],
    [PublishStatuses::PUBLISHED, 'success'],
]);

it('falls back to secondary for unknown states', function () {
    expect(PublishStatuses::getColorForState('unknown'))->toBe('secondary')
        ->and(PublishStatuses::getColorForState(null))->toBe('secondary');
});
