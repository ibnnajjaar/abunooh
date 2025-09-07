<?php

namespace App\Filament\Admin\Resources\Tags;

use UnitEnum;
use BackedEnum;
use App\Models\Tag;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Admin\Resources\Tags\Pages\EditTag;
use App\Filament\Admin\Resources\Tags\Pages\ViewTag;
use App\Filament\Admin\Resources\Tags\Pages\ListTags;
use App\Filament\Admin\Resources\Tags\Pages\CreateTag;
use App\Filament\Admin\Resources\Tags\Schemas\TagForm;
use App\Filament\Admin\Resources\Tags\Tables\TagsTable;
use App\Filament\Admin\Resources\Tags\Schemas\TagInfolist;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string | UnitEnum | null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 110;

    public static function form(Schema $schema): Schema
    {
        return TagForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TagInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TagsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'view' => ViewTag::route('/{record}'),
            'edit' => EditTag::route('/{record}/edit'),
        ];
    }
}
