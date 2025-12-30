<?php

namespace App\Filament\Resources\Purchases;

use App\Filament\Resources\Purchases\PaymentResource\Pages;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\PurchasePayment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PaymentResource extends Resource
{
    protected static ?string $model = PurchasePayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationLabel = 'Pembayaran Pembelian';
    protected static ?string $navigationGroup = 'Purchases';
    protected static ?int $navigationSort = 3;

protected static ?string $tenantRelationshipName = 'purchases_payment';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('purchase_id')
                    ->label('Pembelian')
                    ->searchable()
                    ->preload()
                    ->relationship('purchase', 'reference')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $purchase = Purchase::find($state);
                            $set('reference', 'PAY/' . $purchase?->reference);
                        } else {
                            $set('reference', null);
                        }
                    }),

                TextInput::make('amount')
                    ->label('Jumlah')
                    ->numeric()
                    ->required(),

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
                    ->nullable(),
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
                TextColumn::make('purchase.reference')
                    ->label('Pembelian')
                    ->sortable()
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
