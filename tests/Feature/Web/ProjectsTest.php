<?php

use App\Models\Tag;
use App\Models\Project;
use App\Support\Enums\PublishStatuses;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('displays projects grouped by year and shows tags', function () {
    $tag1 = Tag::factory()->create(['name' => 'Laravel']);
    $tag2 = Tag::factory()->create(['name' => 'Tailwind']);

    $project = Project::factory()->create([
        'title' => 'Project 2024',
        'year' => 2024,
        'description' => 'This is a long description that should be shown in full without trimming any parts of it.',
        'publish_status' => PublishStatuses::PUBLISHED->value,
        'client' => 'Client A',
    ]);

    $project->tags()->attach([$tag1->id, $tag2->id]);

    Project::factory()->create([
        'title' => 'Project 2023',
        'year' => 2023,
        'publish_status' => PublishStatuses::PUBLISHED->value,
        'client' => 'Client B',
    ]);

    $response = $this->get(route('web.projects.index'));

    $response->assertStatus(200);
    $response->assertSee('Project 2024');
    $response->assertSee('Project 2023');
    $response->assertSee('2024 // Projects');
    $response->assertSee('2023 // Projects');
    $response->assertSee('Client A');
    $response->assertSee('Client B');
    $response->assertSee('This is a long description that should be shown in full without trimming any parts of it.');
    $response->assertSee('#Laravel');
    $response->assertSee('#Tailwind');
});

it('only shows published projects', function (PublishStatuses $status) {
    Project::factory()->create(['title' => 'Visible Project', 'publish_status' => PublishStatuses::PUBLISHED->value]);
    Project::factory()->create(['title' => 'Hidden Project', 'publish_status' => $status->value]);

    $this->get(route('web.projects.index'))
        ->assertOk()
        ->assertSee('Visible Project')
        ->assertDontSee('Hidden Project');
})->with([
    PublishStatuses::DRAFT,
    PublishStatuses::PENDING,
    PublishStatuses::REJECTED,
]);

it('orders project groups by year descending', function () {
    Project::factory()->create(['title' => 'Old Project', 'year' => 2019, 'publish_status' => PublishStatuses::PUBLISHED->value]);
    Project::factory()->create(['title' => 'New Project', 'year' => 2024, 'publish_status' => PublishStatuses::PUBLISHED->value]);
    Project::factory()->create(['title' => 'Mid Project', 'year' => 2021, 'publish_status' => PublishStatuses::PUBLISHED->value]);

    $response = $this->get(route('web.projects.index'));

    $response->assertSeeInOrder(['2024 // Projects', '2021 // Projects', '2019 // Projects']);
    expect($response->viewData('project_groups')->keys()->all())->toBe([2024, 2021, 2019]);
});
