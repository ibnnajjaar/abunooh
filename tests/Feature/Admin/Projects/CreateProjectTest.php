<?php

use App\Models\Tag;
use Livewire\Livewire;
use App\Models\Project;
use App\Support\Enums\PublishStatuses;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Projects\Pages\CreateProject;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('can access project create page', function () {
    $this->actingAsAdmin(['projects.view', 'projects.create']);

    Livewire::test(CreateProject::class)
        ->assertOk();
})->group('projects', 'projects.create');

it('can create a project through the form', function () {
    $this->actingAsAdmin(['projects.view', 'projects.create']);

    $title = 'New Portfolio Site';

    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => $title,
            'client' => 'Acme Corp',
            'status' => 'Completed',
            'year' => (int) date('Y'),
            'publish_status' => PublishStatuses::PUBLISHED->value,
            'description' => 'A brand new portfolio build',
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas('projects', [
        'title' => $title,
        'slug' => \Illuminate\Support\Str::slug($title),
        'client' => 'Acme Corp',
        'status' => 'Completed',
        'publish_status' => PublishStatuses::PUBLISHED->value,
    ]);
})->group('projects', 'projects.create');

it('validates project create form data', function () {
    $this->actingAsAdmin(['projects.view', 'projects.create']);

    // Required validation
    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => '',
            // slug is readonly and auto-generated, but will be empty if title is empty
            'client' => '',
            'status' => '',
            'year' => null,
            'publish_status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'required',
            'slug' => 'required',
            'client' => 'required',
            'status' => 'required',
            'year' => 'required',
            'publish_status' => 'required',
        ]);

    // Type and range validation for year
    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => 'X',
            'client' => 'Y',
            'status' => 'Z',
            'year' => 1800,
            'publish_status' => PublishStatuses::DRAFT->value,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'year' => 'min',
        ]);

    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => 'X',
            'client' => 'Y',
            'status' => 'Z',
            'year' => (int) date('Y') + 5,
            'publish_status' => PublishStatuses::DRAFT->value,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'year' => 'max',
        ]);

    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => 'X',
            'client' => 'Y',
            'status' => 'Z',
            'year' => 'invalid',
            'publish_status' => PublishStatuses::DRAFT->value,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'year' => 'numeric',
        ]);
})->group('projects', 'projects.create');

it('cannot access project create page without create permission', function () {
    $this->actingAsAdmin(['projects.view']);

    Livewire::test(CreateProject::class)
        ->assertForbidden();
})->group('projects', 'projects.create');

it('auto-fills the slug from the title', function () {
    $this->actingAsAdmin(['projects.view', 'projects.create']);

    Livewire::test(CreateProject::class)
        ->fillForm(['title' => 'My Great Project'])
        ->assertSchemaStateSet(['slug' => 'my-great-project']);
})->group('projects', 'projects.create');

it('can attach tags when creating a project', function () {
    $this->actingAsAdmin(['projects.view', 'projects.create']);

    $tags = Tag::factory(2)->create();

    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => 'Tagged Project',
            'client' => 'Acme',
            'status' => 'Completed',
            'year' => (int) date('Y'),
            'publish_status' => PublishStatuses::PUBLISHED->value,
            'tags' => $tags->pluck('id')->all(),
            'description' => 'Tagged',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $project = Project::where('slug', 'tagged-project')->firstOrFail();

    expect($project->tags->pluck('id')->sort()->values()->all())
        ->toBe($tags->pluck('id')->sort()->values()->all());
})->group('projects', 'projects.create');

it('requires a unique slug and max lengths', function () {
    $this->actingAsAdmin(['projects.view', 'projects.create']);

    Project::factory()->create(['slug' => 'taken']);

    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => str_repeat('a', 256),
            'slug' => 'taken',
            'client' => str_repeat('a', 256),
            'status' => str_repeat('a', 256),
            'year' => (int) date('Y'),
            'publish_status' => PublishStatuses::DRAFT->value,
        ])
        ->set('data.slug', 'taken')
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'max',
            'slug' => 'unique',
            'client' => 'max',
            'status' => 'max',
        ]);
})->group('projects', 'projects.create');
