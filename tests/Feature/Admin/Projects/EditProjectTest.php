<?php

use App\Models\Tag;
use App\Models\Project;

use function Pest\Livewire\livewire;

use App\Support\Enums\PublishStatuses;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Projects\Pages\EditProject;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot load the project edit page without update permission', function () {
    $this->actingAsAdmin(['projects.view']);

    $project = Project::factory()->create();

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->assertForbidden();
})->group('projects', 'projects.update');

it('can load the project edit page with update permission', function () {
    $this->actingAsAdmin(['projects.view', 'projects.update']);

    $project = Project::factory()->create();

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->assertOk()
        ->assertSchemaStateSet([
            'title' => $project->title,
            'slug' => $project->slug,
            'client' => $project->client,
            'status' => $project->status,
            'year' => $project->year,
            'publish_status' => $project->publish_status->value,
            'description' => $project->description,
        ]);
})->group('projects', 'projects.update');

it('can update a project through the form', function () {
    $this->actingAsAdmin(['projects.view', 'projects.update']);

    $project = Project::factory()->create();
    $tag = Tag::factory()->create();

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->fillForm([
            'title' => 'Updated Project',
            'client' => 'Updated Client',
            'status' => 'Archived',
            'year' => 2020,
            'publish_status' => PublishStatuses::DRAFT->value,
            'tags' => [$tag->id],
            'description' => 'Updated description',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas('projects', [
        'id' => $project->id,
        'title' => 'Updated Project',
        'slug' => 'updated-project',
        'client' => 'Updated Client',
        'status' => 'Archived',
        'year' => 2020,
        'publish_status' => PublishStatuses::DRAFT->value,
    ]);

    expect($project->refresh()->tags->pluck('id')->all())->toBe([$tag->id]);
})->group('projects', 'projects.update');

it('validates project edit form data', function () {
    $this->actingAsAdmin(['projects.view', 'projects.update']);

    $project = Project::factory()->create();
    Project::factory()->create(['slug' => 'other']);

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->fillForm([
            'title' => '',
            'client' => '',
            'status' => '',
            'year' => null,
            'publish_status' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'title' => 'required',
            'client' => 'required',
            'status' => 'required',
            'year' => 'required',
            'publish_status' => 'required',
        ]);

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->fillForm(['slug' => 'other'])
        ->call('save')
        ->assertHasFormErrors(['slug' => 'unique']);

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->fillForm(['slug' => $project->slug])
        ->call('save')
        ->assertHasNoFormErrors();
})->group('projects', 'projects.update');
