<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Sales\SaleReturnPaymentResource\Pages;
use App\Filament\Resources\Sales\SaleReturnPaymentResource\RelationManagers;
use App\Models\Sales\SaleReturn;
use App\Models\Sales\SaleReturnPayment;
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

class SaleReturnPaymentResource extends Resource
{
    protected static ?string $model = SaleReturnPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Sales Return';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Return Payment';
    protected static ?string $tenantRelationshipName = 'sales_return_payment';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sale_return_id')
                    ->label('Sale Return')
                    ->searchable()
                    ->preload()
                    ->relationship('sale_return', 'reference')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $saleReturn = SaleReturn::find($state);
                            $set('reference', 'PYR/' . $saleReturn?->reference);
                            if ($saleReturn) {
                                $set('amount', $saleReturn->due_amount);
                            }
                        } else {
                            $set('reference', null);
                            $set('amount', 0);
                        }
                    }),

                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->reactive(),

                DatePicker::make('date')
                    ->required()
                    ->default(now()),

                TextInput::make('reference')
                    ->label('Payment Reference')
                    ->reactive()
                    ->dehydrated(),

                Select::make('payment_method')
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
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('reference')->searchable(),
                TextColumn::make('sale_return.reference')->label('Sale Return')->sortable()->searchable(),
                TextColumn::make('sale_return.customer.customer_name')->label('Customer')->searchable(),
                TextColumn::make('amount')->money('idr', true),
                TextColumn::make('payment_method')->badge(),
                TextColumn::make('date')->date(),
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
            'index' => Pages\ListSaleReturnPayments::route('/'),
            'create' => Pages\CreateSaleReturnPayment::route('/create'),
            'edit' => Pages\EditSaleReturnPayment::route('/{record}/edit'),
        ];
    }
}
