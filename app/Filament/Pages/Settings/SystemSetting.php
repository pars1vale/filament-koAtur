<?php

namespace App\Filament\Pages\Settings;

use Filament\Forms;
use App\Models\Settings\Currency;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Form;

class SystemSetting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'System Settings';

    // add to settings navigation menu
    protected static ?string $navigationGroup = 'Settings';

    // menu position
    protected static ?int $navigationSort = 4;

    // ambil view dari resource/view/
    protected static string $view = 'filament.pages.settings.setting';

    // custom permission
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'update',
        ];
    }

    // strict access
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(\App\Models\Settings\Setting::first()?->toArray() ?? []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')->label('Company Name')->required(),
                Forms\Components\TextInput::make('company_email')->label('Company Email')->email(),
                Forms\Components\TextInput::make('company_phone')->label('Company Phone'),
                Forms\Components\FileUpload::make('site_logo')
                    ->label('Site Logo')
                    ->disk('public')
                    ->directory('public')
                    ->image(),
                Forms\Components\Select::make('default_currency_id')
                    ->label('Default Currency')
                    ->options(Currency::pluck('currency_name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('default_currency_position'),
                Forms\Components\TextInput::make('notification_email'),
                Forms\Components\TextInput::make('footer_text'),
                Forms\Components\Textarea::make('company_address'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        \App\Models\Settings\Setting::updateOrCreate(['id' => 1], $state);
        Notification::make()
            ->title('Settings updated successfully!')
            ->success()
            ->send();
    }
}
