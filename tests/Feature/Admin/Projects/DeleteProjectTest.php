<?php

use App\Models\Project;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Projects\Pages\EditProject;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('can delete a project from the edit page with delete permission')
    ->todo(note: 'TASK-9: Project is missing from the enforced morph map, so deleting it throws ClassMorphViolationException')
    ->group('projects', 'projects.delete');

it('hides the delete action on the edit page without delete permission', function () {
    $this->actingAsAdmin(['projects.view', 'projects.update']);

    $project = Project::factory()->create();

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->assertActionHidden(DeleteAction::class);
})->group('projects', 'projects.delete');

it('can bulk delete projects with delete permission')
    ->todo(note: 'TASK-9: Project is missing from the enforced morph map, so deleting it throws ClassMorphViolationException')
    ->group('projects', 'projects.delete');

it('detaches tags when a project is deleted')
    ->todo(note: 'TASK-9: Project is missing from the enforced morph map, so deleting it throws ClassMorphViolationException')
    ->group('projects', 'projects.delete');

it('cannot bulk delete projects without delete permission')
    ->todo(note: 'TASK-8: ProjectPolicy has no deleteAny, so bulk delete is allowed for any admin')
    ->group('projects', 'projects.delete');
