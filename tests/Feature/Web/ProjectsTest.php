<?php

use App\Models\Tag;
use App\Models\Project;
use App\Support\Enums\PublishStatuses;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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
