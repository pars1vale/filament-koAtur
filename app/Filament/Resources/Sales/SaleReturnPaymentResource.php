<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Sales\SaleReturnPaymentResource\Pages;
use App\Filament\Resources\Sales\SaleReturnPaymentResource\RelationManagers;
use App\Models\Products\Product;
use App\Models\Sales\SaleReturn;
use App\Models\Sales\SaleReturnPayment;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Sales Return';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('reference')
                    ->label('Reference')
                    ->readOnly()
                    ->dehydrated(true)
                    ->placeholder(function () {
                        $lastId = SaleReturn::max('id') ?? 0;
                        $nextId = $lastId + 1;
                        return 'SR' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                    }),

                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'customer_name')
                    ->required(),

                DateTimePicker::make('date')
                    ->default(now())
                    ->required()
                    ->label('Purchase Date'),

                Section::make('Product Details')
                    ->schema([
                        Repeater::make('details')
                            ->relationship('product_details')
                            ->reactive()
                            ->schema([
                                Hidden::make('id'),

                                Select::make('product_id')
                                    ->relationship('product', 'product_name')
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('product_code', $product->product_code);
                                            $set('unit_price', $product->product_price);
                                            $set('product_tax_amount', 0);
                                            $set('product_discount_amount', 0);
                                            $set('product_discount_type', 'percent');

                                            $item     = $get();
                                            $subTotal = self::calculateProductSubtotal($item, $set, $product);
                                            $set('sub_total', $subTotal);
                                        }
                                        self::calculateDue($set, $get);
                                    }),

                                TextInput::make('product_code')
                                    ->label('Product Code')
                                    ->readOnly(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $product   = Product::find($get('product_id'));
                                        $item      = $get();
                                        $subTotal  = self::calculateProductSubtotal($item, $set, $product);
                                        $set('sub_total', $subTotal);
                                        self::calculateDue($set, $get);
                                    }),

                                TextInput::make('unit_price')
                                    ->numeric()
                                    ->required()
                                    ->readOnly()
                                    ->default(0),

                                TextInput::make('product_discount_amount')
                                    ->label('Discount')
                                    ->numeric()
                                    ->default(0)
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $product   = Product::find($get('product_id'));
                                        $item      = $get();
                                        $subTotal  = self::calculateProductSubtotal($item, $set, $product);
                                        $set('sub_total', $subTotal);
                                        self::calculateDue($set, $get);
                                    }),

                                Select::make('product_discount_type')
                                    ->options([
                                        'fixed'   => 'Fixed',
                                        'percent' => 'Percent',
                                    ])
                                    ->default('percent')
                                    ->dehydrated(),

                                TextInput::make('product_tax_amount')
                                    ->label('Tax Amount')
                                    ->numeric()
                                    ->default(0)
                                    ->readOnly()
                                    ->dehydrated(true),

                                TextInput::make('sub_total')
                                    ->numeric()
                                    ->default(0)
                                    ->readOnly()
                                    ->dehydrated(true)
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->addActionLabel('Add Product')
                            ->afterStateUpdated(function ($state, $set, $get) {
                                self::calculateDue($set, $get);
                            })
                    ])
                    ->collapsible()
                    ->collapsed(false),

                TextInput::make('tax_percentage')
                    ->numeric()
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn($state, $set, $get) => self::calculateDue($set, $get)),

                TextInput::make('tax_amount')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(true),

                TextInput::make('discount_percentage')
                    ->numeric()
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn($state, $set, $get) => self::calculateDue($set, $get)),

                TextInput::make('discount_amount')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(true),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),

                Select::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending'   => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),

                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash'        => 'Cash',
                        'credit_card' => 'Credit Card',
                        'bank'        => 'Bank Transfer',
                        'cheque'      => 'Cheque',
                        'other'       => 'Other',
                    ])
                    ->default('cash')
                    ->required(),

                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(true)
                    ->reactive(),

                TextInput::make('paid_amount')
                    ->numeric()
                    ->label('Paid Amount')
                    ->required()
                    ->default(0)
                    ->live(debounce: 500)
                    ->afterStateUpdated(fn($state, $set, $get) => self::calculateDue($set, $get)),

                TextInput::make('due_amount')
                    ->label('Due Amount')
                    ->numeric()
                    ->readOnly()
                    ->reactive()
                    ->dehydrated(true),

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
                TextColumn::make('sale_return.reference')->label('Sale')->sortable()->searchable(),
                TextColumn::make('amount')->money('idr', true),
                TextColumn::make('payment_method')->badge(),
                TextColumn::make('date')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
