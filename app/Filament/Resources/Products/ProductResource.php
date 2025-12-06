<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\ProductResource\Pages;
use App\Filament\Resources\Products\ProductResource\RelationManagers;
use App\Models\Products\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\ViewEntry;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Products';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_name')->required(),
                Forms\Components\TextInput::make('product_code')
                    ->default(fn () => Product::generateProductCode())
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled()
                    ->dehydrated(true),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'category_name')
                    ->required(),
                Forms\Components\Select::make('unit_id')
                    ->relationship('unit', 'name')
                    ->required(),
                Forms\Components\TextInput::make('product_cost')->numeric()->required(),
                Forms\Components\TextInput::make('product_price')->numeric()->required(),
                Forms\Components\TextInput::make('product_quantity')->numeric()->required(),
                Forms\Components\TextInput::make('product_stock_alert')->numeric()->default(0),
                Forms\Components\TextInput::make('product_order_tax')->label('Tax (%)')->numeric()->nullable(),
                Forms\Components\Select::make('product_tax_type')->options([
                    0 => 'Exclusive',
                    1 => 'Inclusive',
                ])->nullable(),
                Forms\Components\Textarea::make('product_note')->nullable(),
                Forms\Components\FileUpload::make('product_image')
                    ->disk('public')
                    ->directory('products')
                    ->image()
                    ->visibility('public')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('product_image')
                    ->label('Image')
                    ->disk('public')
                    ->size(60),
                Tables\Columns\TextColumn::make('category.category_name')->label('Category'),
                Tables\Columns\TextColumn::make('product_code')->label('Code')->searchable(),
                Tables\Columns\TextColumn::make('product_name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('product_cost')->label('Cost')->money('idr'),
                Tables\Columns\TextColumn::make('product_price')->label('Price')->money('idr'),
                Tables\Columns\TextColumn::make('product_quantity')->label('Quantity'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
        ->schema([
            Section::make('Product Details')
                ->schema([
                    // add view and print barcode
                    // ViewEntry::make('barcode')
                    //     ->view('infolists.products.barcode')
                    //     ->getStateUsing(fn($record) => $record),
                    // end, add view and print barcode

                    Grid::make(3)
                        ->schema([
                            Group::make([
                                TextEntry::make('product_name')->label('Product'),
                                TextEntry::make('category.category_name')->label('Category'),
                                TextEntry::make('unit.name')->label('Unit'),
                                TextEntry::make('product_code')->label('SKU'),
                                TextEntry::make('product_stock_alert')->label('Minimum Qty'),
                            ]),
                            Group::make([
                                TextEntry::make('product_quantity')->label('Quantity'),
                                TextEntry::make('product_order_tax')->label('Tax')->suffix('%'),
                                TextEntry::make('product_tax_type')->label('Discount Type'),
                                TextEntry::make('product_price')->label('Price')->money('idr'),
                                TextEntry::make('product_note')->label('Description'),
                            ]),
                            Group::make([
                                ImageEntry::make('product_image')
                                    ->disk('public')
                                    ->height(250)
                                    ->label('Image'),
                            ]),
                        ]),
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
            'index' => Pages\ListProducts::route('/'),
            // 'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }
}
