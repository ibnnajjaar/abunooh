<?php

use Livewire\Livewire;
use App\Models\Project;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Projects\Pages\ListProjects;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('can view the list projects page', function () {
    $this->actingAsAdmin(['view projects']);

    $projects = collect([
        Project::create([
            'title' => 'Alpha Project',
            'slug' => 'alpha-project',
            'client' => 'Acme',
            'status' => 'Completed',
            'year' => (int) date('Y'),
            'order_column' => 1,
            'publish_status' => \App\Support\Enums\PublishStatuses::PUBLISHED->value,
            'description' => 'Alpha description',
        ]),
        Project::create([
            'title' => 'Beta Build',
            'slug' => 'beta-build',
            'client' => 'Globex',
            'status' => 'In Progress',
            'year' => (int) date('Y'),
            'order_column' => 2,
            'publish_status' => \App\Support\Enums\PublishStatuses::DRAFT->value,
            'description' => 'Beta description',
        ]),
        Project::create([
            'title' => 'Gamma Launch',
            'slug' => 'gamma-launch',
            'client' => 'Umbrella',
            'status' => 'Pending',
            'year' => (int) date('Y'),
            'order_column' => 3,
            'publish_status' => \App\Support\Enums\PublishStatuses::PENDING->value,
            'description' => 'Gamma description',
        ]),
    ]);

    livewire(ListProjects::class)
        ->assertOk()
        ->assertCanSeeTableRecords($projects)
        ->searchTable('Beta')
        ->assertCanSeeTableRecords($projects->slice(1, 1))
        ->assertCanNotSeeTableRecords($projects->except([1]));
})->group('projects');

it('shows the create action on list page', function () {
    $this->actingAsAdmin(['view projects', 'create projects']);

    Livewire::test(ListProjects::class)
        ->assertActionVisible('create');
})->group('projects');
