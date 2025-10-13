<?php

namespace App\Filament\Resources\Expenses;

use App\Filament\Resources\Expenses\ExpenseResource\Pages;
use App\Filament\Resources\Expenses\ExpenseResource\RelationManagers;
use App\Models\Expenses\Expense;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    // Grouping to Expenses menu
    public static function getNavigationGroup(): ?string
    {
        return 'Expenses';
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
                TextInput::make('reference')
                    ->label('Reference')
                    ->readOnly()
                    ->dehydrated(true)
                    ->placeholder(function () {
                        return Expense::generateReference();
                    }),
                DateTimePicker::make('date')
                    ->label('Date')
                    ->required(),
                Textarea::make('details')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('amount')
                    ->numeric()
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'category_name')
                    ->required()
                    ->label('Category'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference'),
                TextColumn::make('date')
                    ->sortable(),
                TextColumn::make('details')
                    ->wrap(),
                TextColumn::make('amount')
                    ->sortable(),
                TextColumn::make('category.category_name')
                    ->label('Category')
                    ->searchable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
