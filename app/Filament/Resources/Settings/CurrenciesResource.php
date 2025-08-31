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
    protected static ?string $navigationGroup = 'Settings';
    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('currency_name')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('symbol')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('thousand_separator')
                    ->required()
                    ->maxLength(1),
                Forms\Components\TextInput::make('decimal_separator')
                    ->required()
                    ->maxLength(1),
                Forms\Components\TextInput::make('exchange_rate')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('currency_name'),
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('symbol'),
                Tables\Columns\TextColumn::make('thousand_separator'),
                Tables\Columns\TextColumn::make('decimal_separator'),
                Tables\Columns\TextColumn::make('exchange_rate'),
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
