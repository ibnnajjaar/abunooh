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
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog8Tooth;

    protected static string | UnitEnum | null $navigationGroup = 'Configuration';

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
            'home_page_description' => get_setting('home_page_description', ''),
            'socials' => get_setting('socials', []),
        ]);
    }

    public function form(Schema $schema): Schema
    {

        return $schema
            ->components([
                Section::make('Site Settings')
                       ->description('Update your site settings here.')
                       ->schema([
                           TextInput::make('site_name')
                                    ->inlineLabel()
                               ->disabled(! auth('web_admin')->user()->can('edit settings'))
                                    ->required(),
                           TextInput::make('home_page_title')
                                    ->label('Hero Section Title')
                                    ->inlineLabel()
                                    ->disabled(! auth('web_admin')->user()->can('edit settings'))
                                    ->required(),
                           TextInput::make('home_page_description')
                                    ->label('Site Description')
                                    ->inlineLabel()
                                    ->disabled(! auth('web_admin')->user()->can('edit settings'))
                                    ->required(),
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
                                   ->disabled(! auth('web_admin')->user()->can('edit settings')),
                       ]),
            ])->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                  ->label('Save Settings')
                ->visible(! auth('web_admin')->user()->cannot('edit settings'))
                  ->submit('save'),
        ];
    }

    public function save(): void
    {
        if (auth('web_admin')->user()->cannot('edit settings')) {
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
        $settings->home_page_description = $data['home_page_description'];
        $settings->socials = $data['socials'];
        $settings->save();

        Notification::make()
                    ->title('Settings saved successfully!')
                    ->success()
                    ->send();
    }
}
