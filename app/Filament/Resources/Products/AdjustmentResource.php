<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\AdjustmentResource\Pages;
use App\Filament\Resources\Products\AdjustmentResource\RelationManagers;
use App\Models\Adjustments\Adjustment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class AdjustmentResource extends Resource
{
    protected static ?string $model = Adjustment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Penyesuaian';
    protected static ?string $navigationGroup = 'Penyesuaian Stok';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Adjustment::query()->with('products'))
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reference')
                    ->label('No. Referensi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('Produk')
                    ->counts('products'),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextEntry::make('date')
                            ->label('Tanggal')
                            ->date('d M, Y'),

                        TextEntry::make('reference')
                            ->label('No. Referensi'),
                    ]),

                RepeatableEntry::make('products')
                    ->label('')
                    ->schema([
                        TextEntry::make('product.product_name')
                            ->label('Nama Produk'),
                        TextEntry::make('product.product_code')
                            ->label('Kode Produk'),
                        TextEntry::make('quantity')
                            ->label('Jumlah'),
                        TextEntry::make('type')
                            ->label('Tipe')
                            ->formatStateUsing(fn(string $state) => $state === 'add'
                                ? '(+) Tambah'
                                : '(-) Kurang'),
                    ])
                    ->columns(4)
                    ->contained(false)
                    ->columnSpanFull()
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
            'index' => Pages\ListAdjustments::route('/'),
            'create' => Pages\CreateAdjustment::route('/create'),
            'edit' => Pages\EditAdjustment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->forUserOutlets();
    }
}
