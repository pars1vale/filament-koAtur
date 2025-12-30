<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\CurrenciesResource\Pages;
use App\Filament\Resources\CurrenciesResource\RelationManagers;
use App\Models\Settings\Currency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class CurrenciesResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Mata Uang';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 1;

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    //     public static function isScopedToTenant(): bool
    // {
    //     return false;
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('currency_name')
                    ->label('Nama Mata Uang')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('symbol')
                    ->label('Simbol')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('thousand_separator')
                    ->label('Separator Ribuan')
                    ->required()
                    ->maxLength(1),
                Forms\Components\TextInput::make('decimal_separator')
                    ->label('Separator Desimal')
                    ->required()
                    ->maxLength(1),
                Forms\Components\TextInput::make('exchange_rate')
                    ->label('Nilai Tukar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('currency_name')
                    ->label('Nama Mata Uang'),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode'),
                Tables\Columns\TextColumn::make('symbol')
                    ->label('Simbol'),
                Tables\Columns\TextColumn::make('thousand_separator')
                    ->label('Separator Ribuan'),
                Tables\Columns\TextColumn::make('decimal_separator')
                    ->label('Separator Desimal'),
                Tables\Columns\TextColumn::make('exchange_rate')
                    ->label('Nilai Tukar'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCurrencies::route('/'),
            // 'create' => Pages\CreateCurrencies::route('/create'),
            'edit' => Pages\EditCurrencies::route('/{record}/edit'),
        ];
    }
}
