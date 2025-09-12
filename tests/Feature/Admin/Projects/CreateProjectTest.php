<?php

use Livewire\Livewire;
use App\Support\Enums\PublishStatuses;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Projects\Pages\CreateProject;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('can access project create page', function () {
    $this->actingAsAdmin(['view projects', 'create projects']);

    Livewire::test(CreateProject::class)
        ->assertOk();
})->group('projects', 'projects.create');

it('can create a project through the form', function () {
    $this->actingAsAdmin(['view projects', 'create projects']);

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
    $this->actingAsAdmin(['view projects', 'create projects']);

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
