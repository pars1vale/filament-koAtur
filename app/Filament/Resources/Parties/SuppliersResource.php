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
    protected static ?string $navigationLabel = 'Supplier';
    protected static ?string $navigationGroup = 'Parties';
    protected static ?int $navigationSort = 2;

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
                    ->label('Nama Supplier')
                    ->required()
                    ->maxLength(50),
                TextInput::make('supplier_email')
                    ->label('Email Supplier')
                    ->required()
                    ->email()
                    ->maxLength(100),
                TextInput::make('supplier_phone')
                    ->label('Telepon Supplier')
                    ->required()
                    ->maxLength(20),
                TextInput::make('city')
                    ->label('Kota')
                    ->required()
                    ->maxLength(50),
                TextInput::make('country')
                    ->label('Negara')
                    ->required()
                    ->maxLength(50),
                Textarea::make('address')
                    ->label('Alamat')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier_name')
                    ->label('Nama Supplier'),
                TextColumn::make('supplier_email')
                    ->label('Email Supplier'),
                TextColumn::make('supplier_phone')
                    ->label('Telepon Supplier'),
                TextColumn::make('city')
                    ->label('Kota'),
                TextColumn::make('country')
                    ->label('Negara'),
                TextColumn::make('address')
                    ->label('Alamat')
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
