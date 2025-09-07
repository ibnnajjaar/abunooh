<?php

use App\Models\Tag;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Permission;
use App\Filament\Admin\Resources\Tags\Pages\ListTags;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot view the list tags page without permissions', function () {
    $this->actingAsAdmin();

    livewire(ListTags::class)
        ->assertForbidden();
});

it('can view the list tags page with permissions', function () {
    $this->actingAsAdmin(['view tags']);
    $tags = Tag::factory(3)->create();

    livewire(ListTags::class)
        ->assertOk()
        ->assertCanSeeTableRecords($tags);
});

it('shows tags in table and can search', function () {
    $this->actingAsAdmin(['view tags']);

    $tags = collect([
        Tag::create(['name' => 'Laravel', 'slug' => 'laravel', 'color' => '#ff2d20']),
        Tag::create(['name' => 'Filament', 'slug' => 'filament', 'color' => '#10b981']),
        Tag::create(['name' => 'Livewire', 'slug' => 'livewire', 'color' => '#22d3ee']),
    ]);

    Livewire::test(ListTags::class)
        ->assertCanSeeTableRecords($tags)
        ->searchTable('Filament')
        ->assertCanSeeTableRecords($tags->slice(1, 1))
        ->assertCanNotSeeTableRecords($tags->except([1]));
})->group('tags');

it('shows the create action when user can create tags', function () {
    $this->actingAsAdmin(['view tags', 'create tags']);

    Livewire::test(ListTags::class)
        ->assertActionVisible('create');
})->group('tags');

it('hides the create action when user cannot create tags', function () {
    $this->actingAsAdmin(['view tags']);

    Livewire::test(ListTags::class)
        ->assertActionHidden('create');
})->group('tags');
