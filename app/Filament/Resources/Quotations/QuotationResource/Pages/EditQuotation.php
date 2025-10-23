<?php

namespace App\Filament\Resources\Quotations\QuotationResource\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use App\Models\Products\Product;
use App\Models\Parties\Customer;
use App\Models\Quotations\Quotation;
use App\Models\Quotations\QuotationDetail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditQuotation extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = QuotationResource::class;

    protected static string $view = 'filament.pages.quotations.edit-quotation';

    public $quotation_id;
    public $reference;
    public $date;
    public $product_id;
    public $customer_id;
    public $customer_name;
    public $items = [];
    public $tax_percentage = 0;
    public $discount_percentage = 0;
    public $shipping_amount = 0;
    public $tax_amount = 0;
    public $discount_amount = 0;
    public $total_amount = 0;
    public $grand_total = 0;
    public $status = 'Pending';
    public $note = '';

    public function mount($record)
    {
        $quotation = Quotation::with('details')->findOrFail($record);
        
        $this->quotation_id = $quotation->id;
        $this->reference = $quotation->reference;
        $this->date = $quotation->date;
        $this->customer_id = $quotation->customer_id;
        $this->customer_name = $quotation->customer_name;
        $this->tax_percentage = $quotation->tax_percentage ?? 0;
        $this->discount_percentage = $quotation->discount_percentage ?? 0;
        $this->shipping_amount = $quotation->shipping_amount ?? 0;
        $this->tax_amount = $quotation->tax_amount ?? 0;
        $this->discount_amount = $quotation->discount_amount ?? 0;
        $this->grand_total = $quotation->grand_total ?? 0;
        $this->status = $quotation->status ?? 'Pending';
        $this->note = $quotation->note ?? '';
        
        $this->items = $quotation->details->map(function ($item) {
            return [
                'id' => $item->product_id,
                'name' => $item->product_name,
                'code' => $item->product_code,
                'net_unit_price' => $item->unit_price,
                'stock' => $item->product->product_quantity ?? 0,
                'quantity' => $item->quantity,
                'tax' => $item->product_tax_amount,
                'sub_total' => $item->sub_total,
                'product_tax_type' => $item->product->product_tax_type ?? 0,
                'product_order_tax' => $item->product->product_order_tax ?? 0,
            ];
        })->toArray();

        $this->recalculateTotal();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('product_id')
                ->label('Search Product')
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
                    ->label('Reference')
                    ->default(fn() => $this->reference)
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->options(Customer::pluck('customer_name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state) {
                        $customer = Customer::find($state);
                        $this->customer_name = $customer?->customer_name;
                    }),

                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->default(fn() => $this->date)
                    ->required()
                    ->native(false),
            ]),
        ];
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
            $tax = $price * ($item['product_order_tax'] ?? 0) / 100;

            $subtotal = $quantity * ($price + $tax);
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

    public function update()
    {
        if (empty($this->items)) {
            Notification::make()
                ->title('Please add at least one product.')
                ->danger()
                ->send();
            return;
        }

        $outletId = Auth::user()->outlets()->first()?->id ?? 1;

        DB::transaction(function () use ($outletId) {
            $quotation = Quotation::findOrFail($this->quotation_id);

            $quotation->update([
                'outlet_id' => $outletId,
                'date' => $this->date,
                'customer_id' => $this->customer_id,
                'customer_name' => $this->customer_name,
                'tax_percentage' => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount' => $this->shipping_amount,
                'tax_amount' => $this->tax_amount,
                'discount_amount' => $this->discount_amount,
                'total_amount' => $this->total_amount,
                'grand_total' => $this->grand_total,
                'status' => $this->status,
                'note' => $this->note,
            ]);

            // Hapus detail lama
            $quotation->details()->delete();

            // Simpan ulang detail baru
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
            ->title('Quotation updated successfully.')
            ->success()
            ->send();

        return redirect(static::getResource()::getUrl('index'));
    }
}
