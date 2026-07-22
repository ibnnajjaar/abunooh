<?php

namespace App\Filament\Admin\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Support\Settings\SiteSettings;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
use Filament\Navigation\NavigationItem;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Enums\SubNavigationPosition;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog8Tooth;

    protected static string | UnitEnum | null $navigationGroup = 'Configuration';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    protected static ?int $navigationSort = 2002;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return true;
    }

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => get_setting('site_name', ''),
            'home_page_title' => get_setting('home_page_title', ''),
            'site_description' => get_setting('site_description', ''),
            'socials' => get_setting('socials', []),
        ]);
    }

    public function getSubNavigation(): array
    {
        return [
            NavigationItem::make('Site Settings')
                          ->url('#site-settings')
                          ->icon(Heroicon::OutlinedGlobeAlt),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([

                Section::make('Site Settings')
                       ->id('site-settings')
                       ->description('Update your site settings here.')
                       ->schema([
                           TextInput::make('site_name')
                                    ->inlineLabel()
                               ->disabled(! auth('web_admin')->user()->can('settings.update'))
                                    ->required(),
                           MarkdownEditor::make('site_description')
                                    ->label('Site Description')
                                    ->inlineLabel()
                                    ->disabled(! auth('web_admin')->user()->can('settings.update'))
                                    ->required(),
                       ]),
                Section::make('Hero Section')
                       ->id('hero-section')
                       ->description('Update your hero section details here.')
                       ->schema([
                           RichEditor::make('home_page_title')
                                    ->label('Hero Section Title')
                                    ->inlineLabel()
                                    ->disabled(! auth('web_admin')->user()->can('settings.update'))
                                    ->required(),

                       ]),
                Section::make('Socials')
                       ->id('social-links')
                       ->description('Manage your social media links.')
                       ->schema([
                           Repeater::make('socials')
                                   ->schema([
                                       TextInput::make('name')
                                                ->required(),
                                       TextInput::make('link')
                                                ->url()
                                                ->required(),
                                   ])
                                   ->columns(2)
                                   ->inlineLabel()
                                   ->disabled(! auth('web_admin')->user()->can('settings.update')),
                       ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                  ->label('Save Settings')
                ->visible(! auth('web_admin')->user()->cannot('settings.update'))
                  ->submit('save'),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(1);
    }

    public function save(): void
    {
        if (auth('web_admin')->user()->cannot('settings.update')) {
            Notification::make()
                        ->title('You do not have permission to edit settings.')
                        ->danger()
                        ->send();
            return;
        }

        $data = $this->form->getState();

        // Save to Spatie Settings
        $settings = app(SiteSettings::class);
        $settings->site_name = $data['site_name'];
        $settings->home_page_title = $data['home_page_title'];
        $settings->site_description = $data['site_description'];
        $settings->socials = $data['socials'];
        $settings->save();

        Notification::make()
                    ->title('Settings saved successfully!')
                    ->success()
                    ->send();
    }
}
