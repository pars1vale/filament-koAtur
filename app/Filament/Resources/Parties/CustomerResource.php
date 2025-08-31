<?php

namespace App\Filament\Resources\Parties;

use App\Filament\Resources\Parties\CustomerResource\Pages;
use App\Filament\Resources\Parties\CustomerResource\RelationManagers;
use App\Models\Parties\Customer;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    // Grouping to parties menu
    public static function getNavigationGroup(): ?string
    {
        return 'Parties';
    }

    // Menu order Position
    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('customer_name')
                    ->required()
                    ->maxLength(50),
                TextInput::make('customer_email')
                    ->required()
                    ->email()
                    ->maxLength(100),
                TextInput::make('customer_phone')
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
                TextColumn::make('customer_name'),
                TextColumn::make('customer_email'),
                TextColumn::make('customer_phone'),
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
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListCustomers::route('/'),
            // 'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
