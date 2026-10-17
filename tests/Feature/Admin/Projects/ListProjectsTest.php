<?php

use Livewire\Livewire;
use App\Models\Project;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Projects\Pages\ListProjects;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('can view the list projects page', function () {
    $this->actingAsAdmin(['projects.view']);

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
    $this->actingAsAdmin(['projects.view', 'projects.create']);

    Livewire::test(ListProjects::class)
        ->assertActionVisible('create');
})->group('projects');

it('cannot view the list projects page without permissions', function () {
    $this->actingAsAdmin();

    livewire(ListProjects::class)
        ->assertForbidden();
})->group('projects');

it('hides the create action when user cannot create projects', function () {
    $this->actingAsAdmin(['projects.view']);

    livewire(ListProjects::class)
        ->assertActionHidden('create');
})->group('projects');

it('sorts projects by order column by default', function () {
    $this->actingAsAdmin(['projects.view']);

    $second = Project::factory()->create(['order_column' => 2]);
    $first = Project::factory()->create(['order_column' => 1]);
    $third = Project::factory()->create(['order_column' => 3]);

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords([$first, $second, $third], inOrder: true);
})->group('projects');

it('can search projects by client', function () {
    $this->actingAsAdmin(['projects.view']);

    $acme = Project::factory()->create(['client' => 'Acme']);
    $globex = Project::factory()->create(['client' => 'Globex']);

    livewire(ListProjects::class)
        ->searchTable('Globex')
        ->assertCanSeeTableRecords([$globex])
        ->assertCanNotSeeTableRecords([$acme]);
})->group('projects');

it('can sort projects by year', function () {
    $this->actingAsAdmin(['projects.view']);

    $old = Project::factory()->create(['year' => 2001]);
    $new = Project::factory()->create(['year' => 2020]);

    livewire(ListProjects::class)
        ->sortTable('year')
        ->assertCanSeeTableRecords([$old, $new], inOrder: true)
        ->sortTable('year', 'desc')
        ->assertCanSeeTableRecords([$new, $old], inOrder: true);
})->group('projects');
