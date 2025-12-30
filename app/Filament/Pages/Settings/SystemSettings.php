<?php

namespace App\Filament\Pages\Settings;

use Filament\Forms;
use App\Models\Settings\Currency;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Illuminate\Container\Attributes\Auth;

class SystemSettings extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Sistem';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.pages.settings.setting';

    public ?array $data = [];

    public function mount(): void
    {
        // Load dari database (misal tabel settings id=1)
        $this->form->fill(\App\Models\Settings\Setting::first()?->toArray() ?? []);
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')->label('Nama Perusahaan')->required(),
                Forms\Components\TextInput::make('company_email')->label('Email Perusahaan')->email(),
                Forms\Components\TextInput::make('company_phone')->label('Kontak Perusahaan'),
                Forms\Components\FileUpload::make('site_logo')
                    ->label('Logo Website')
                    ->disk('public')
                    ->directory('public')
                    ->image(),
                Forms\Components\Select::make('default_currency_id')
                    ->label('Mata Uang Utama')
                    ->options(Currency::pluck('currency_name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('default_currency_position')
                    ->label('Posisi Mata Uang Default'),
                Forms\Components\TextInput::make('notification_email')
                    ->label('Email Notifikasi'),
                Forms\Components\TextInput::make('footer_text')
                    ->label('Footer Text'),
                Forms\Components\Textarea::make('company_address')
                    ->label('Alamat Perusahaan'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        \App\Models\Settings\Setting::updateOrCreate(['id' => 1], $state);
        Notification::make()
            ->title('Pengaturan berhasil diperbarui!')
            ->success()
            ->send();
    }
}
