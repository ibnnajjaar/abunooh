<?php

use App\Models\Tag;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Tags\Pages\CreateTag;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);


it('cannot access create page without create permission', function () {
    $this->actingAsAdmin();

    Livewire::test(CreateTag::class)
            ->assertForbidden();

})->group('tags', 'tags.create');

it('can access create page with create permission', function () {
    $this->actingAsAdmin(['tags.view', 'tags.create']);

    Livewire::test(CreateTag::class)
            ->assertOk();

})->group('tags', 'tags.create');

it('can create a tag through the form', function () {
    $this->actingAsAdmin(['tags.view', 'tags.create']);

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
    $this->actingAsAdmin(['tags.view', 'tags.create']);

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

it('auto-fills the slug from the name', function () {
    $this->actingAsAdmin(['tags.view', 'tags.create']);

    Livewire::test(CreateTag::class)
        ->fillForm(['name' => 'Hello World Tag'])
        ->assertSchemaStateSet(['slug' => 'hello-world-tag']);
})->group('tags', 'tags.create');

it('requires a unique slug', function () {
    $this->actingAsAdmin(['tags.view', 'tags.create']);

    Tag::factory()->create(['slug' => 'taken']);

    Livewire::test(CreateTag::class)
        ->fillForm([
            'name' => 'Taken',
            'slug' => 'taken',
            'color' => '#abcdef',
        ])
        ->call('create')
        ->assertHasFormErrors(['slug' => 'unique']);
})->group('tags', 'tags.create');
