<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Sales\SalePaymentResource\Pages;
use App\Filament\Resources\Sales\SalePaymentResource\RelationManagers;
use App\Models\Sales\Sale;
use App\Models\Sales\SalePayment;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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

class SalePaymentResource extends Resource
{
    protected static ?string $model = SalePayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Pembayaran Penjualan';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 4;

    protected static ?string $tenantRelationshipName = 'sales_payment';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sale_id')
                    ->label('Penjualan')
                    ->searchable()
                    ->preload()
                    ->relationship('sale', 'reference')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $sale = Sale::find($state);
                            $set('reference', 'PAY/' . $sale?->reference);
                        } else {
                            $set('reference', null);
                        }
                    }),

                TextInput::make('amount')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->reactive(),

                DatePicker::make('date')
                    ->label('Tanggal')
                    ->required()
                    ->default(now()),

                TextInput::make('reference')
                    ->label('Referensi Pembayaran')
                    ->readOnly()
                    ->reactive()
                    ->dehydrated(),

                Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash'        => 'Cash',
                        'credit_card' => 'Credit Card',
                        'bank'        => 'Bank Transfer',
                        'cheque'      => 'Cheque',
                        'other'       => 'Other',
                    ])
                    ->default('cash')
                    ->required(),

                Textarea::make('note')
                    ->label('Catatan')
                    ->placeholder('Catatan (opsional)')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('reference')
                    ->label('No. Referensi')
                    ->searchable(),
                TextColumn::make('sale.reference')
                    ->label('Penjualan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sale.customer.customer_name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('idr', true),
                TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->badge(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalePayments::route('/'),
            'create' => Pages\CreateSalePayment::route('/create'),
            'edit' => Pages\EditSalePayment::route('/{record}/edit'),
        ];
    }
}
