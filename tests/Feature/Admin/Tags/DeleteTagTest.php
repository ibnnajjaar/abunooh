<?php

use App\Models\Tag;

use function Pest\Livewire\livewire;

use Filament\Actions\DeleteBulkAction;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

use App\Filament\Admin\Resources\Tags\Pages\ListTags;

uses(\Illuminate\Foundation\Testing\LazilyRefreshDatabase::class);

it('can bulk delete tags with delete permission', function () {
    $this->actingAsAdmin(['tags.view', 'tags.delete']);

    $tags = Tag::factory(3)->create();

    livewire(ListTags::class)
        ->callTableBulkAction(DeleteBulkAction::class, $tags)
        ->assertNotified();

    foreach ($tags as $tag) {
        assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
})->group('tags', 'tags.delete');

it('cannot bulk delete tags without delete permission', function () {
    $this->actingAsAdmin(['tags.view']);

    $tags = Tag::factory(2)->create();

    livewire(ListTags::class)
        ->assertTableBulkActionHidden(DeleteBulkAction::class);

    foreach ($tags as $record) {
        assertDatabaseHas('tags', ['id' => $record->id]);
    }
})->group('tags', 'tags.delete');
