<?php

namespace App\Filament\Resources\Purchases;

use App\Filament\Resources\Purchases\PaymentReturnResource\Pages;
use App\Filament\Resources\Purchases\PaymentReturnResource\RelationManagers;
use App\Models\Purchases\PurchaseReturn;
use App\Models\Purchases\PurchaseReturnPayment;
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

class PaymentReturnResource extends Resource
{
    protected static ?string $model = PurchaseReturnPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationGroup = 'Purchases Return';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Return Payment';

    // protected static ?string $tenantRelationshipName = 'purchases_return_payment';
    protected static ?string $tenantRelationshipName = 'purchases_return_payment';
    // protected static ?string $tenantOwnershipRelationshipName = 'outlet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('purchase_return_id')
                    ->label('Purchase')
                    ->searchable()
                    ->preload()
                    ->relationship('purchase_return', 'reference')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $purchaseReturn = PurchaseReturn::find($state);
                            $set('reference', 'PYR/' . $purchaseReturn?->reference);
                        } else {
                            $set('reference', null);
                        }
                    }),

                TextInput::make('amount')
                    ->numeric()
                    ->required(),

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
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('reference')->searchable(),
                TextColumn::make('purchase_return.reference')->label('Purchase')->sortable()->searchable(),
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
            'index' => Pages\ListPaymentReturns::route('/'),
            'create' => Pages\CreatePaymentReturn::route('/create'),
            'edit' => Pages\EditPaymentReturn::route('/{record}/edit'),
        ];
    }
}
