<?php

use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

use Spatie\Permission\Models\Permission;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Tags\Pages\CreateTag;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);


it('cannot access create page without create permission', function () {
    $this->actingAsAdmin();

    Livewire::test(CreateTag::class)
            ->assertForbidden();

})->group('tags', 'tags.create');

it('can access create page with create permission', function () {
    $this->actingAsAdmin(['view tags', 'create tags']);

    Livewire::test(CreateTag::class)
            ->assertOk();

})->group('tags', 'tags.create');

it('can create a tag through the form', function () {
    $this->actingAsAdmin(['view tags', 'create tags']);

    Livewire::test(CreateTag::class)
        ->fillForm([
            'name' => 'New Tag',
            'slug' => 'new-tag',
            'color' => '#abcdef',
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas('tags', [
        'name' => 'New Tag',
        'slug' => 'new-tag',
        'color' => '#abcdef',
    ]);
})->group('tags', 'tags.create');

it('can validate tag form data', function () {
    $this->actingAsAdmin(['view tags', 'create tags']);

    Livewire::test(CreateTag::class)
            ->fillForm([
                'name'  => '',
                'slug'  => '',
                'color' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name'  => 'required',
                'slug'  => 'required',
                'color' => 'required',
            ]);

    Livewire::test(CreateTag::class)
            ->fillForm([
                'name'  => str_repeat('a', 258),
                'slug'  => str_repeat('a', 258),
                'color' => 'invalid-color',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'max',
                'slug' => 'max',
                'color' => 'regex',
            ]);

})->group('tags', 'tags.create');
