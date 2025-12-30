<?php

namespace App\Filament\Pages\Quotations;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\Products\Product;
use App\Models\Parties\Customer;
use App\Models\Quotations\Quotation;
use App\Models\Quotations\QuotationDetail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateQuotation extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationLabel = 'Buat Penawaran Harga';
    protected static ?string $navigationGroup = 'Penawaran Harga';
    protected static ?string $title = 'Buat Penawaran Harga';
    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    
    protected static ?string $slug = 'create-quotation';
    protected static string $view = 'filament.pages.quotations.create-quotation';

    public $reference;
    public $date;
    public $product_id;
    public $customer_id;
    public $customer_name;
    public $tax_percentage = 0;
    public $discount_percentage = 0;
    public $shipping_amount = 0;
    public $tax_amount = 0;
    public $discount_amount = 0;
    public $total_amount = 0;
    public $grand_total = 0;
    public $status = 'Pending';
    public $note;
    public $items = [];

    public function mount(): void
    {
        $this->reference = $this->generateReference();
        $this->date = now()->toDateString();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('product_id')
                ->label('Cari Produk')
                ->placeholder('Pilih Produk')
                ->searchable()
                ->getSearchResultsUsing(fn(string $query) => 
                    Product::query()
                        ->where('product_name', 'like', "%{$query}%")
                        ->orWhere('product_code', 'like', "%{$query}%")
                        ->limit(10)
                        ->pluck('product_name', 'id')
                )
                ->getOptionLabelUsing(fn($value): ?string => Product::find($value)?->product_name)
                ->reactive()
                ->afterStateUpdated(fn($state) => $this->addProduct($state)),

            Forms\Components\Grid::make(3)->schema([
                Forms\Components\TextInput::make('reference')
                    ->label('No. Referensi')
                    ->default(fn() => $this->reference)
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\Select::make('customer_id')
                    ->label('Pelanggan')
                    ->placeholder('Pilih Pelanggan')
                    ->options(Customer::pluck('customer_name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state) {
                        $customer = Customer::find($state);
                        $this->customer_name = $customer?->customer_name;
                    }),

                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal')
                    ->default(now())
                    ->required()
                    ->native(false),
            ]),
        ];
    }

    private function generateReference(): string
    {
        $lastQuotation = Quotation::orderBy('id', 'desc')->first();

        if ($lastQuotation && preg_match('/QT-(\d+)/', $lastQuotation->reference, $matches))
        {
            $nextNumber = ((int) $matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        $formattedNumber = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return 'QT-' . $formattedNumber;
    }

    public function addProduct($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        $tax = $product->product_price * $product->product_order_tax / 100;

        if (!collect($this->items)->pluck('id')->contains($product->id)) {
            $this->items[] = [
                'id' => $product->id,
                'name' => $product->product_name,
                'code' => $product->product_code,
                'net_unit_price' => $product->product_price,
                'stock' => $product->product_quantity,
                'quantity' => 1,
                'product_tax_type' => $product->product_tax_type,
                'product_order_tax' => $product->product_order_tax,
                'tax' => $tax,
                'sub_total' => $product->product_price,
            ];
        }

        $this->recalculateTotal();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->recalculateTotal();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, [
            'items',
            'tax_percentage',
            'discount_percentage',
            'shipping_amount',
        ])) {
            $this->recalculateTotal();
        }
        $this->recalculateTotal();
    }

    private function recalculateTotal()
    {
        foreach ($this->items as $key => &$item) {
            $quantity = $item['quantity'] ?? 1;
            $price = $item['net_unit_price'] ?? 0;
            $tax = $item['net_unit_price'] * $item['product_order_tax'] / 100;

            if ($item['product_tax_type'] == 0) {
                $subtotal = $quantity * ($price + $tax);
            } else {
                $subtotal = $quantity * $price;
            }

            $item['tax'] = $tax;
            $item['sub_total'] = $subtotal;
        }
        unset($item);
        
        $this->tax_percentage = (float) $this->tax_percentage;
        $this->discount_percentage = (float) $this->discount_percentage;
        $this->shipping_amount = (float) $this->shipping_amount;

        $subTotal = collect($this->items)->sum('sub_total');
        $this->discount_amount = ($subTotal * $this->discount_percentage) / 100;
        $totalAfterDiscount = $subTotal - $this->discount_amount;
        $this->tax_amount = ($totalAfterDiscount * $this->tax_percentage) / 100;
        $this->grand_total = $totalAfterDiscount + $this->tax_amount + $this->shipping_amount;
        $this->total_amount = $this->grand_total;
    }

    public function save()
    {
        if (empty($this->items)) {
            Notification::make()
                ->title('Silakan tambahkan minimal satu produk.')
                ->danger()
                ->send();
            return;
        }

        $outletId = Auth::user()->outlets()->first()?->id ?? 1;

        DB::transaction(function () use ($outletId) {
            $quotation = Quotation::create([
                'outlet_id' => $outletId,
                'date' => $this->date,
                'reference' => $this->reference,
                'customer_id' => $this->customer_id,
                'customer_name' => $this->customer_name,
                'tax_percentage' => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount' => $this->shipping_amount,
                'tax_amount' => $this->tax_amount,
                'discount_amount' => $this->discount_amount,
                'total_amount' => $this->total_amount,
                'status' => $this->status,
                'note' => $this->note,
            ]);

            foreach ($this->items as $item) {
                if ($item['product_tax_type'] == 0) {
                    $tax = $item['net_unit_price'] * $item['product_order_tax'] / 100;
                    $price = $item['net_unit_price'] + $tax;
                    QuotationDetail::create([
                        'outlet_id' => $outletId,
                        'quotation_id' => $quotation->id,
                        'product_id' => $item['id'],
                        'product_name' => $item['name'],
                        'product_code' => $item['code'],
                        'quantity' => $item['quantity'],
                        'price' => $price,
                        'unit_price' => $item['net_unit_price'],
                        'sub_total' => $item['sub_total'],
                        'product_discount_amount' => 0,
                        'product_discount_type' => 'fixed',
                        'product_tax_amount' => $tax,
                    ]);
                } else {
                    $tax = $item['net_unit_price'] * $item['product_order_tax'] / 100;
                    $unit_price = $item['net_unit_price'] - $tax;
                    QuotationDetail::create([
                        'outlet_id' => $outletId,
                        'quotation_id' => $quotation->id,
                        'product_id' => $item['id'],
                        'product_name' => $item['name'],
                        'product_code' => $item['code'],
                        'quantity' => $item['quantity'],
                        'price' => $item['net_unit_price'],
                        'unit_price' => $unit_price,
                        'sub_total' => $item['sub_total'],
                        'product_discount_amount' => 0,
                        'product_discount_type' => 'fixed',
                        'product_tax_amount' => $tax,
                    ]);
                }
            }
        });

        Notification::make()
            ->title('Penawaran harga berhasil dibuat.')
            ->success()
            ->send();

        $this->reset([
            'customer_id',
            'customer_name',
            'items',
            'note',
            'total_amount',
            'tax_percentage',
            'discount_percentage',
            'shipping_amount',
            'status',
        ]);

        $this->reset(['customer_id', 'customer_name', 'items', 'note', 'total_amount']);
        $this->reference = $this->generateReference();
    }
}