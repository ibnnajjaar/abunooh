<?php

use App\Models\Tag;

use function Pest\Livewire\livewire;

use App\Filament\Admin\Resources\Tags\Pages\ViewTag;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);
it('cannot access view page without view permission', function () {
    $this->actingAsAdmin();

    $tag = Tag::create(['name' => 'Alpha', 'slug' => 'alpha', 'color' => '#123456']);

    livewire(ViewTag::class, [
        'record' => $tag->getKey(),
    ])->assertForbidden();
})->group('tags', 'tags.view');

it('shows tag details in view page', function () {
    $this->actingAsAdmin(['tags.view']);

    $tag = Tag::create(['name' => 'Alpha', 'slug' => 'alpha', 'color' => '#123456']);

    livewire(ViewTag::class, [
        'record' => $tag->getKey(),
    ])->assertOk()
      ->assertSchemaStateSet([
          'name'  => $tag->name,
          'slug'  => $tag->slug,
          'color' => $tag->color,
      ]);
})->group('tags', 'tags.view');
