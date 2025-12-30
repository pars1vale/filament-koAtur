<?php

namespace App\Filament\Resources\Quotations;

use App\Filament\Resources\Quotations\QuotationResource\Pages;
use App\Models\Quotations\Quotation;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationLabel = 'Daftar Penawaran Harga';
    protected static ?string $navigationGroup = 'Penawaran Harga';
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reference')
                    ->label('No. Referensi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'info' => 'Pending',
                        'success' => 'Sent',
                    ])
                    ->label('Status'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Harga')
                    ->money('idr'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => url('/admin/' . Auth::user()->outlets()->first()->id . '/quotations/quotations/' . $record->id . '/view-quotation'))
                    ->color('gray'),
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
            'view-quotation' => Pages\ViewQuotation::route('/{record}/view-quotation'),
        ];
    }
}
