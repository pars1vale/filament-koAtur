<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\UnitsResource\Pages;
use App\Filament\Resources\UnitsResource\RelationManagers;
use App\Models\Settings\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitsResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }

        public static function isScopedToTenant(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('short_name')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('operator')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('operation_value')
                    ->required()
                    ->maxLength(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('short_name'),
                Tables\Columns\TextColumn::make('operator'),
                Tables\Columns\TextColumn::make('operation_value'),
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
            'index' => Pages\ListUnits::route('/'),
            // 'create' => Pages\CreateUnits::route('/create'),
            'edit' => Pages\EditUnits::route('/{record}/edit'),
        ];
    }
}
