<?php

use App\Models\Tag;
use App\Models\Post;
use App\Models\Project;
use App\Support\Enums\PublishStatuses;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('defaults the order column to 1', function () {
    expect((new Project())->order_column)->toBe(1);
});

it('casts the publish status to an enum', function () {
    $project = Project::factory()->create(['publish_status' => PublishStatuses::DRAFT->value]);

    expect($project->publish_status)->toBe(PublishStatuses::DRAFT);
});

it('belongs to many tags', function () {
    $project = Project::factory()->create();
    $tags = Tag::factory(2)->create();

    $project->tags()->attach($tags);

    expect($project->tags)->toHaveCount(2)
        ->and($project->tags->pluck('id')->sort()->values()->all())->toBe($tags->pluck('id')->sort()->values()->all());
});

it('scopes published projects', function () {
    $published = Project::factory()->create(['publish_status' => PublishStatuses::PUBLISHED->value]);
    Project::factory()->create(['publish_status' => PublishStatuses::DRAFT->value]);

    expect(Project::published()->pluck('id')->all())->toBe([$published->id]);
});

it('scopes pending projects', function () {
    $pending = Project::factory()->create(['publish_status' => PublishStatuses::PENDING->value]);
    Project::factory()->create(['publish_status' => PublishStatuses::PUBLISHED->value]);

    expect(Project::pending()->pluck('id')->all())->toBe([$pending->id]);
});

it('renders the description as markdown', function () {
    $project = Project::factory()->make(['description' => '# Title']);

    expect(trim($project->formatted_description))->toBe('<h1>Title</h1>');
});

it('returns an empty featured image without media', function () {
    $project = Project::factory()->create();

    expect($project->featured_image)->toBe('');
});

it('uses morph aliases for content models', function () {
    expect((new Project())->getMorphClass())->toBe('project')
        ->and((new Tag())->getMorphClass())->toBe('tag')
        ->and((new Post())->getMorphClass())->toBe('post');
});
