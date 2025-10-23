<?php

namespace App\Filament\Resources\Quotations;

use App\Filament\Resources\Quotations\QuotationResource\Pages;
use App\Filament\Resources\Quotations\QuotationResource\RelationManagers;
use App\Models\Quotations\Quotation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'All Quotations';
    protected static ?string $navigationGroup = 'Quotations';

    public static function form(Form $form): Form
    {
        //
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'info' => 'Pending',
                        'success' => 'Sent',
                    ])
                    ->label('Status'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Price')
                    ->money('idr'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => url('/admin/' . Auth::user()->outlets()->first()->id . '/quotations/quotations/' . $record->id . '/view-quotation'))
                    ->color('gray'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
