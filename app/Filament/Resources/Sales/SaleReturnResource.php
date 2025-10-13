<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Sales\SaleReturnResource\Pages;
use App\Filament\Resources\Sales\SaleReturnResource\RelationManagers;
use App\Models\Products\Product;
use App\Models\Sales\SaleReturn;
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

class SaleReturnResource extends Resource
{
    protected static ?string $model = SaleReturn::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-turn-up-left';

    protected static ?string $navigationGroup = 'Sales Return';
    protected static ?int $navigationSort = 1;

    protected static ?string $tenantRelationshipName = 'sales_return';

    // Helper perhitungan
    public static function calculateTax(callable $set, callable $get, float $baseTotal): float
    {
        $taxPercentage = (float) ($get('tax_percentage') ?? 0);
        $taxAmount = ($baseTotal * $taxPercentage) / 100;

        $set('tax_amount', round($taxAmount, 2));

        return $taxAmount;
    }

    public static function calculateDiscount(callable $set, callable $get, float $baseTotal): float
    {
        $discountPercentage = (float) ($get('discount_percentage') ?? 0);
        $discountAmount = ($baseTotal * $discountPercentage) / 100;

        $set('discount_amount', round($discountAmount, 2));

        return $discountAmount;
    }

    protected static function calculateProductSubtotal(array $item, callable $set = null, Product $product = null): float
    {
        $quantity = (float) ($item['quantity'] ?? 0);
        $unitPrice = (float) ($item['unit_price'] ?? 0);

        $discountValue = (float) ($item['product_discount_amount'] ?? 0);
        $discountType = $item['product_discount_type'] ?? 'percent';

        $base = $quantity * $unitPrice;

        // Hitung diskon
        $discountAmount = $discountType === 'percent'
            ? ($base * $discountValue / 100)
            : $discountValue;

        // Ambil tax dari tabel Product
        $taxRate = $product?->product_order_tax ?? 0;
        $taxType = $product?->product_tax_type ?? 0;

        if ($taxType === 0) {
            // EXCLUSIVE
            $taxAmount = $taxRate > 0 ? (($base - $discountAmount) * $taxRate / 100) : 0;
            $subtotal = $base - $discountAmount + $taxAmount;
        } else {
            // INCLUSIVE
            $netBase = $base - $discountAmount;
            $taxAmount = $taxRate > 0 ? ($netBase - ($netBase / (1 + $taxRate / 100))) : 0;
            $subtotal = $netBase;
        }

        if ($set) {
            $set('product_discount_amount', round($discountAmount, 2));
            $set('product_tax_amount', round($taxAmount, 2));
        }

        return round($subtotal, 2);
    }

    public static function calculateBaseTotal(callable $get): float
    {
        $details = $get('details') ?? [];

        $total = collect($details)->sum(function ($item) {
            $product = isset($item['product_id']) ? Product::find($item['product_id']) : null;
            return self::calculateProductSubtotal($item, null, $product);
        });

        return round($total, 2);
    }

    public static function calculateGrandTotal(callable $set, callable $get): float
    {
        $baseTotal = self::calculateBaseTotal($get);
        $taxAmount = self::calculateTax($set, $get, $baseTotal);
        $discountAmount = self::calculateDiscount($set, $get, $baseTotal);

        $grandTotal = $baseTotal + $taxAmount - $discountAmount;

        // Update all related fields
        $set('total_amount', round($grandTotal, 2));

        // Calculate due amount
        $paid = (float) ($get('paid_amount') ?? 0);
        $dueAmount = max(0, round($grandTotal - $paid, 2));
        $set('due_amount', $dueAmount);

        // Update payment status based on amounts
        self::updatePaymentStatus($set, $get, $grandTotal, $paid);

        return $grandTotal;
    }

    protected static function updatePaymentStatus(callable $set, callable $get, float $grandTotal, float $paid): void
    {
        if ($paid <= 0) {
            $set('payment_status', 'unpaid');
        } elseif ($paid >= $grandTotal) {
            $set('payment_status', 'paid');
        } else {
            $set('payment_status', 'partial');
        }
    }

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
                        return 'SRT' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                    }),

                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'customer_name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn($state, $set, $get) => self::calculateGrandTotal($set, $get)),

                DateTimePicker::make('date')
                    ->default(now())
                    ->required()
                    ->label('Return Date'),

                Section::make('Product Details')
                    ->schema([
                        Repeater::make('details')
                            ->relationship('product_details')
                            ->schema([
                                Hidden::make('id'),

                                Select::make('product_id')
                                    ->relationship('product', 'product_name')
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('product_code', $product->product_code);
                                            $set('unit_price', $product->product_price);
                                            $set('product_tax_amount', 0);
                                            $set('product_discount_amount', 0);
                                            $set('product_discount_type', 'percent');

                                            $item = $get();
                                            $subTotal = self::calculateProductSubtotal($item, $set, $product);
                                            $set('sub_total', $subTotal);
                                        }
                                        self::calculateGrandTotal($set, $get);
                                    }),

                                TextInput::make('product_code')
                                    ->label('Product Code')
                                    ->readOnly(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $product = Product::find($get('product_id'));
                                        $item = $get();
                                        $subTotal = self::calculateProductSubtotal($item, $set, $product);
                                        $set('sub_total', $subTotal);
                                        self::calculateGrandTotal($set, $get);
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
                                        $product = Product::find($get('product_id'));
                                        $item = $get();
                                        $subTotal = self::calculateProductSubtotal($item, $set, $product);
                                        $set('sub_total', $subTotal);
                                        self::calculateGrandTotal($set, $get);
                                    }),

                                Select::make('product_discount_type')
                                    ->options([
                                        'fixed' => 'Fixed',
                                        'percent' => 'Percent',
                                    ])
                                    ->default('percent')
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $product = Product::find($get('product_id'));
                                        $item = $get();
                                        $subTotal = self::calculateProductSubtotal($item, $set, $product);
                                        $set('sub_total', $subTotal);
                                        self::calculateGrandTotal($set, $get);
                                    }),

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
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                self::calculateGrandTotal($set, $get);
                            })
                            ->deleteAction(function ($set, $get) {
                                self::calculateGrandTotal($set, $get);
                            })
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // Global Discounts and Taxes
                Section::make('Global Adjustments')
                    ->schema([
                        TextInput::make('tax_percentage')
                            ->numeric()
                            ->default(0)
                            ->label('Tax Percentage (%)')
                            ->live()
                            ->afterStateUpdated(fn($state, $set, $get) => self::calculateGrandTotal($set, $get)),

                        TextInput::make('tax_amount')
                            ->numeric()
                            ->readOnly()
                            ->label('Tax Amount')
                            ->dehydrated(true),

                        TextInput::make('discount_percentage')
                            ->numeric()
                            ->default(0)
                            ->label('Discount Percentage (%)')
                            ->live()
                            ->afterStateUpdated(fn($state, $set, $get) => self::calculateGrandTotal($set, $get)),

                        TextInput::make('discount_amount')
                            ->numeric()
                            ->readOnly()
                            ->label('Discount Amount')
                            ->dehydrated(true),
                    ])
                    ->columns(2),

                // Totals Section
                Section::make('Totals')
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->readOnly()
                            ->dehydrated(true)
                            ->prefix('IDR'),

                        TextInput::make('paid_amount')
                            ->numeric()
                            ->label('Paid Amount')
                            ->required()
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, $set, $get) => self::calculateGrandTotal($set, $get))
                            ->prefix('IDR'),

                        TextInput::make('due_amount')
                            ->label('Due Amount')
                            ->numeric()
                            ->readOnly()
                            ->dehydrated(true)
                            ->prefix('IDR'),
                    ])
                    ->columns(3),

                // Status Section
                Section::make('Status')
                    ->schema([
                        Select::make('status')
                            ->label('Return Status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required(),

                        Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'paid' => 'Paid',
                                'partial' => 'Partial',
                            ])
                            ->default('unpaid')
                            ->required()
                            ->dehydrated(),

                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'credit_card' => 'Credit Card',
                                'bank' => 'Bank Transfer',
                                'cheque' => 'Cheque',
                                'other' => 'Other',
                            ])
                            ->default('cash')
                            ->required(),
                    ])
                    ->columns(3),

                Textarea::make('note')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->searchable(),
                TextColumn::make('customer.customer_name')->searchable(),
                TextColumn::make('date'),
                TextColumn::make('status')->badge(),
                TextColumn::make('payment_status')->badge(),
                TextColumn::make('paid_amount')->money('idr'),
                TextColumn::make('total_amount')->money('idr'),
                TextColumn::make('due_amount')->money('idr'),
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
            'index' => Pages\ListSaleReturns::route('/'),
            'create' => Pages\CreateSaleReturn::route('/create'),
            'edit' => Pages\EditSaleReturn::route('/{record}/edit'),
        ];
    }
}
