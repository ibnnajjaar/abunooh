<?php

use App\Models\Project;
use App\Support\Enums\PublishStatuses;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays projects grouped by year', function () {
    Project::factory()->create([
        'title' => 'Project 2024',
        'year' => 2024,
        'publish_status' => PublishStatuses::PUBLISHED->value,
        'client' => 'Client A',
    ]);

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
});
