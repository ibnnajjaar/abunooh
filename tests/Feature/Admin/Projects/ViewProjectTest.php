<?php

use App\Models\Project;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Projects\Pages\ViewProject;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot access view page without view permission', function () {
    $this->actingAsAdmin();

    $project = Project::factory()->create();

    livewire(ViewProject::class, [
        'record' => $project->getKey(),
    ])->assertForbidden();
})->group('projects', 'projects.view');

it('shows project details in view page', function () {
    $this->actingAsAdmin(['projects.view']);

    $project = Project::factory()->create();

    livewire(ViewProject::class, [
        'record' => $project->getKey(),
    ])->assertOk()
      ->assertSchemaStateSet([
          'title'  => $project->title,
          'slug'   => $project->slug,
          'client' => $project->client,
          'status' => $project->status,
          'year'   => $project->year,
      ]);
})->group('projects', 'projects.view');

it('shows the edit action only with update permission', function () {
    $project = Project::factory()->create();

    $this->actingAsAdmin(['projects.view']);

    livewire(ViewProject::class, ['record' => $project->getKey()])
        ->assertActionHidden('edit');

    $this->actingAsAdmin(['projects.view', 'projects.update']);

    livewire(ViewProject::class, ['record' => $project->getKey()])
        ->assertActionVisible('edit');
})->group('projects', 'projects.view');
