<?php

namespace App\Filament\Resources\Parties;

use App\Filament\Resources\Parties\SuppliersResource\Pages;
use App\Filament\Resources\Parties\SuppliersResource\RelationManagers;
use App\Models\Parties\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuppliersResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    // Grouping to parties menu
    public static function getNavigationGroup(): ?string
    {
        return 'Parties';
    }

    // Menu order Position
    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('supplier_name')
                    ->required()
                    ->maxLength(50),
                TextInput::make('supplier_email')
                    ->required()
                    ->email()
                    ->maxLength(100),
                TextInput::make('supplier_phone')
                    ->required()
                    ->maxLength(20),
                TextInput::make('city')
                    ->required()
                    ->maxLength(50),
                TextInput::make('country')
                    ->required()
                    ->maxLength(50),
                Textarea::make('address')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier_name'),
                TextColumn::make('supplier_email'),
                TextColumn::make('supplier_phone'),
                TextColumn::make('city'),
                TextColumn::make('country'),
                TextColumn::make('address')
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSuppliers::route('/'),
            // 'create' => Pages\CreateSuppliers::route('/create'),
            'edit' => Pages\EditSuppliers::route('/{record}/edit'),
        ];
    }
}
