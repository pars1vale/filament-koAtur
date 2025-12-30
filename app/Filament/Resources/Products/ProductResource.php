<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\ProductResource\Pages;
use App\Models\Products\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Facades\Filament;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $navigationGroup = 'Produk';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('outlet_id')
                    ->default(fn () => Filament::getTenant()?->id)
                    ->required(),
                Forms\Components\TextInput::make('product_name')
                    ->label('Nama Produk')
                    ->required(),
                Forms\Components\TextInput::make('product_code')
                    ->label('Kode Produk')
                    ->default(fn () => 
                        Filament::getTenant()
                            ? Product::generateProductCode(Filament::getTenant()->id)
                            : null
                    )
                    ->disabled()
                    ->dehydrated(true)
                    ->required()
                    ->unique(
                        table: 'products',
                        column: 'product_code',
                        ignoreRecord: true,
                        modifyRuleUsing: fn ($rule) =>
                            $rule->where(
                                'outlet_id',
                                Filament::getTenant()?->id
                            )
                    ),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'category_name')
                    ->required(),
                Forms\Components\Select::make('unit_id')
                    ->label('Satuan')
                    ->relationship('unit', 'name')
                    ->required(),
                Forms\Components\TextInput::make('product_cost')
                    ->label('Biaya Produk')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('product_price')
                    ->label('Harga Jual')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('product_quantity')
                    ->label('Jumlah Produk')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('product_stock_alert')
                    ->label('Notifikasi Stok Produk')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('product_order_tax')
                    ->label('Pajak (%)')
                    ->numeric()
                    ->nullable(),
                Forms\Components\Select::make('product_tax_type')
                    ->label('Jenis Pajak Produk')
                    ->options([
                        0 => 'Exclusive',
                        1 => 'Inclusive',
                    ])
                    ->nullable(),
                Forms\Components\Textarea::make('product_note')
                    ->label('Catatan Produk')
                    ->nullable(),
                Forms\Components\FileUpload::make('product_image')
                    ->label('Gambar Produk')
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
                    ->label('Gambar')
                    ->disk('public')
                    ->size(60),
                Tables\Columns\TextColumn::make('category.category_name')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('product_code')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_cost')
                    ->label('Biaya')
                    ->money('idr'),
                Tables\Columns\TextColumn::make('product_price')
                    ->label('Harga')
                    ->money('idr'),
                Tables\Columns\TextColumn::make('product_quantity')
                    ->label('Jumlah'),
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
                    Grid::make(3)
                        ->schema([
                            Group::make([
                                TextEntry::make('product_name')
                                    ->label('Produk'),
                                TextEntry::make('category.category_name')
                                    ->label('Kategori'),
                                TextEntry::make('unit.name')
                                    ->label('Satuan'),
                                TextEntry::make('product_code')
                                    ->label('Kode Produk (SKU)'),
                                TextEntry::make('product_stock_alert')
                                    ->label('Jumlah Minimum'),
                            ]),
                            Group::make([
                                TextEntry::make('product_quantity')
                                    ->label('Jumlah'),
                                TextEntry::make('product_order_tax')
                                    ->label('Pajak')
                                    ->suffix('%'),
                                TextEntry::make('product_tax_type')
                                    ->label('Jenis Diskon'),
                                TextEntry::make('product_price')
                                    ->label('Harga')
                                    ->money('idr'),
                                TextEntry::make('product_note')
                                    ->label('Deskripsi'),
                            ]),
                            Group::make([
                                ImageEntry::make('product_image')
                                    ->label('Gambar')
                                    ->disk('public')
                                    ->height(250),
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
