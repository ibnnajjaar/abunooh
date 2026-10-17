<?php

use App\Models\Tag;
use App\Models\Project;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('belongs to many projects', function () {
    $tag = Tag::factory()->create();
    $projects = Project::factory(2)->create();

    $tag->projects()->attach($projects);

    expect($tag->projects)->toHaveCount(2);
});

it('can be created without a color', function () {
    $tag = Tag::factory()->withoutColor()->create();

    expect($tag->color)->toBeNull();
});
