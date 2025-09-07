<?php

use App\Models\Tag;
use App\Models\User;
use Livewire\Livewire;

use App\Filament\Admin\Resources\Users\Pages\EditUser;
use function Pest\Laravel\actingAs;

use Spatie\Permission\Models\Permission;

use function Pest\Livewire\livewire;
use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Admin\Resources\Tags\Pages\EditTag;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('cannot load the tag edit page without update permission', function () {
    $this->actingAsAdmin();

    $tag = Tag::factory()->create();

    livewire(EditTag::class, [
        'record' => $tag->id,
    ])
        ->assertForbidden();
})->group('tags', 'tags.update');

it('can load the tag edit page with update permission', function () {
    $this->actingAsAdmin(['view tags', 'update tags']);

    $tag = Tag::factory()->create();

    livewire(EditTag::class, [
        'record' => $tag->id,
    ])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $tag->name,
            'slug' => $tag->slug,
            'color' => $tag->color,
        ]);
})->group('tags', 'tags.update');

it('can update a tag through the form', function () {
    $this->actingAsAdmin(['view tags', 'update tags']);

    $tag = Tag::create(['name' => 'Old', 'slug' => 'old', 'color' => '#000000']);

    Livewire::test(EditTag::class, [
        'record' => $tag->getKey(),
    ])->fillForm([
        'name' => 'Updated',
        'slug' => 'updated',
        'color' => '#ffffff',
    ])->call('save')
      ->assertNotified()
      ->assertHasNoFormErrors();

    assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => 'Updated',
        'slug' => 'updated',
        'color' => '#ffffff',
    ]);
})->group('tags', 'tags.update');

it('can validate tag form data', function () {
    $this->actingAsAdmin(['view tags', 'update tags']);

    $tag = Tag::create(['name' => 'Test', 'slug' => 'test', 'color' => '#000000']);

    Livewire::test(EditTag::class, [
        'record' => $tag->getKey(),
    ])->fillForm([
        'name'  => '',
        'slug'  => '',
        'color' => '',
    ])->call('save')
            ->assertHasFormErrors([
                'name'  => 'required',
                'slug'  => 'required',
                'color' => 'required',
            ]);

    Livewire::test(EditTag::class, [
        'record' => $tag->getKey(),
    ])->fillForm([
        'name'  => str_repeat('a', 258),
        'slug'  => str_repeat('a', 258),
        'color' => 'invalid-color',
    ])->call('save')
            ->assertHasFormErrors([
                'name'  => 'max',
                'slug'  => 'max',
                'color' => 'regex',
            ]);
})->group('tags', 'tags.update');


