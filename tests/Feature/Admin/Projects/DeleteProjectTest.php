<?php

use App\Models\Project;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

use Filament\Actions\DeleteBulkAction;

use function Pest\Laravel\assertDatabaseMissing;

use App\Filament\Admin\Resources\Projects\Pages\EditProject;
use App\Filament\Admin\Resources\Projects\Pages\ListProjects;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('can delete a project from the edit page with delete permission', function () {
    $this->actingAsAdmin(['projects.view', 'projects.update', 'projects.delete']);

    $project = Project::factory()->create();

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseMissing('projects', ['id' => $project->id]);
})->group('projects', 'projects.delete');

it('hides the delete action on the edit page without delete permission', function () {
    $this->actingAsAdmin(['projects.view', 'projects.update']);

    $project = Project::factory()->create();

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->assertActionHidden(DeleteAction::class);
})->group('projects', 'projects.delete');

it('can bulk delete projects with delete permission', function () {
    $this->actingAsAdmin(['projects.view', 'projects.delete']);

    $projects = Project::factory(3)->create();

    livewire(ListProjects::class)
        ->callTableBulkAction(DeleteBulkAction::class, $projects)
        ->assertNotified();

    foreach ($projects as $project) {
        assertDatabaseMissing('projects', ['id' => $project->id]);
    }
})->group('projects', 'projects.delete');

it('detaches tags when a project is deleted', function () {
    $this->actingAsAdmin(['projects.view', 'projects.update', 'projects.delete']);

    $project = Project::factory()->hasTags(2)->create();

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->callAction(DeleteAction::class);

    assertDatabaseMissing('project_tag', ['project_id' => $project->id]);
})->group('projects', 'projects.delete');

it('cannot bulk delete projects without delete permission')
    ->todo(note: 'TASK-8: ProjectPolicy has no deleteAny, so bulk delete is allowed for any admin')
    ->group('projects', 'projects.delete');
