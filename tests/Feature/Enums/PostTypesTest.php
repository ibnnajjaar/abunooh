<?php

use App\Support\Enums\PostTypes;

it('provides labels and options for every case', function () {
    expect(PostTypes::labels())->toBe([
        'post' => 'Post',
        'page' => 'Page',
        'case_study' => 'Case Study',
        'logo' => 'Logo',
    ])
        ->and(PostTypes::options())->toBe(PostTypes::labels())
        ->and(PostTypes::getKeys())->toBe(['post', 'page', 'case_study', 'logo']);
});

it('returns the label and color of a case', function (PostTypes $type, string $label, string $color) {
    expect($type->getLabel())->toBe($label)
        ->and($type->getColor())->toBe($color);
})->with([
    [PostTypes::POST, 'Post', 'success'],
    [PostTypes::PAGE, 'Page', 'warning'],
    [PostTypes::CASE_STUDY, 'Case Study', 'danger'],
    [PostTypes::LOGO, 'Logo', 'primary'],
]);
