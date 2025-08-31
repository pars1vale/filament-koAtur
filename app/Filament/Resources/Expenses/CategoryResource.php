<?php

namespace App\Filament\Resources\Expenses;

use App\Filament\Resources\Expenses\CategoryResource\Pages;
use App\Filament\Resources\Expenses\CategoryResource\RelationManagers;
use App\Models\Expenses\Category;
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
use Livewire\Attributes\Title;

use function Laravel\Prompts\textarea;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    // Grouping to Expenses menu
    public static function getNavigationGroup(): ?string
    {
        return 'Expenses';
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
                TextInput::make('category_name')
                    ->label('Category Name')
                    ->required()
                    ->maxLength(50),
                Textarea::make('category_description')
                    ->label('Category Description')
                    ->nullable()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category_name')
                    ->searchable()
                    ->label('Category Name'),
                TextColumn::make('category_description')
                    ->label('Category Description')
                    ->wrap(),
                TextColumn::make('expenses_sum_amount')
                    ->label('Expenses Count')
                    ->sortable(),
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

    // total amount each category using query
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withSum('expenses', 'amount');
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
            'index' => Pages\ListCategories::route('/'),
            // 'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
