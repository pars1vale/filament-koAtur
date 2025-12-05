<?php

namespace App\Filament\Pages\POSInterface;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;

use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\Sales\Sale;
use App\Models\Sales\SaleDetail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cashier extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $title = 'Cashier';
    protected static ?string $navigationGroup = 'POS Interface';

    protected static string $view = 'filament.pages.pos-interface.cashier';

    public $search = '';
    public $selectedCategory = '';

    public $discount_percent = 0;
    public $customer_name = '';

    public $cart = [];

    public function mount()
    {
        $this->form->fill([
            'search' => '',
            'selectedCategory' => '',
            'discount_percent' => 0,
            'customer_name' => '',
        ]);
    }

    // Start Load categories
    public function getCategoriesProperty()
    {
        return Category::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('category_name', 'asc')
            ->get();
    }
    // End Load categories

    // Start Load products
    public function getProductsProperty()
    {
        return Product::query()
            ->when($this->search, fn ($q) =>
                $q->where('product_name', 'like', '%' . $this->search . '%')
            )
            ->when($this->selectedCategory, fn ($q) =>
                $q->where('category_id', $this->selectedCategory)
            )
            ->paginate(10);
    }
    // End Load products

    // Start Form (SEARCH + CATEGORY + DISCOUNT% + CUSTOMER)
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2)
                ->schema([
                    // Start Search Bar
                    Forms\Components\TextInput::make('search')
                        ->placeholder('Search product...')
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            $this->search = $state;
                        }),
                    // End Search Bar

                    // Start Category Dropdown
                    Forms\Components\Select::make('selectedCategory')
                        ->options(
                            $this->categories->pluck('category_name', 'id')
                        )
                        ->placeholder('All Categories')
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            $this->selectedCategory = $state;
                        }),
                    // End Category Dropdown
                ]),

            Forms\Components\Grid::make(2)
                ->schema([
                // Start Discount Input
                Forms\Components\TextInput::make('discount_percent')
                    ->label('Discount (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0)
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => $this->discount_percent = (int) $state),
                // End Discount Input

                // Start Customer Name Input
                Forms\Components\TextInput::make('customer_name')
                    ->label('Customer Name')
                    ->placeholder('Enter customer name...')
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => $this->customer_name = $state),
                // End Customer Name Input
            ]),
        ];
    }
    // End Form (SEARCH + CATEGORY + DISCOUNT% + CUSTOMER)
    
    // Start Cart Function
    public function selectProduct($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        if (!isset($this->cart[$productId])) {
            $this->cart[$productId] = [
                'name' => $product->product_name,
                'price' => $product->product_price,
                'qty' => 1,
            ];
        } else {
            $this->cart[$productId]['qty']++;
        }
    }
    // End Cart Function
    
    // Start Add Quantity
    public function increase($id)
    {
        $this->cart[$id]['qty']++;
    }
    // End Add Quantity

    // Start Sub Quantity
    public function decrease($id)
    {
        if ($this->cart[$id]['qty'] > 1) {
            $this->cart[$id]['qty']--;
        } else {
            unset($this->cart[$id]);
        }
    }
    // End Sub Quantity

    // Start Clear Cart
    public function clearAll()
    {
        $this->cart = [];
        $this->discount_percent = 0;
        $this->customer_name = '';
        
        $this->form->fill([
            'discount_percent' => 0,
            'customer_name' => '',
        ]);
    }
    // End Clear Cart

    // Start Count Subtotal
    public function getSubtotalProperty()
    {
        return collect($this->cart)
            ->sum(fn ($i) => $i['qty'] * $i['price']);
    }
    // End Count Subtotal

    // Start Discount Value
    public function getDiscountValueProperty()
    {
        return ($this->subtotal * $this->discount_percent) / 100;
    }
    // End Discount Value

    // Start Count Total
    public function getTotalProperty()
    {
        return $this->subtotal - $this->discountValue;
    }
    // End Count Total

    // Start Print Bill
    public function printBill()
    {
        if (empty($this->cart)) {
            $this->dispatchBrowserEvent('notify', ['message' => 'Cart is empty!']);
            return;
        }

        return $this->saveSaleAndPrint('cash');
    }
    // End Print Bill

    // Start Save Sales + Sale Details + Generate PDF
    public function saveSaleAndPrint($method)
    {
        DB::beginTransaction();

        try {

            // Start Generate next reference: SL0001
            $nextId = (Sale::max('id') ?? 0) + 1;
            $reference = 'SL' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            // End Generate next reference

            // Start Save Sales
            $sale = Sale::create([
                'outlet_id' => Auth::user()->outlet_id,
                'date' => now(),
                'reference' => $reference,
                'customer_id' => null,
                'tax_percentage' => 0,
                'tax_amount' => 0,
                'discount_percentage' => $this->discount_percent,
                'discount_amount' => $this->discountValue,
                'shipping_amount' => 0,
                'total_amount' => $this->total,
                'paid_amount' => $this->total,
                'due_amount' => 0,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => $method, // FIXED = 'cash'
                'note' => "Sales of cashier with reference {$reference}",
            ]);
            // End Save Sales

            // Start Save Sale Details
            foreach ($this->cart as $productId => $item) {
                $product = Product::find($productId);

                if ($product->product_tax_type == 0) {
                    $tax = $item['price'] * $product->product_order_tax / 100;
                    $price = $item['price'] + $tax;
                    SaleDetail::create([
                        'outlet_id' => Auth::user()->outlet_id,
                        'sale_id' => $sale->id,
                        'product_id' => $productId,
                        'product_code' => $product->product_code,
                        'quantity' => $item['qty'],
                        'unit_price' => $item['price'],
                        'sub_total' => $price * $item['qty'],
                        'product_discount_amount' => 0,
                        'product_discount_type' => 'percent',
                        'product_tax_amount' => $tax,
                    ]);
                } else {
                    $tax = $item['price'] * $product->product_order_tax / 100;
                    $unit_price = $item['price'] - $tax;
                    SaleDetail::create([
                        'outlet_id' => Auth::user()->outlet_id,
                        'sale_id' => $sale->id,
                        'product_id' => $productId,
                        'product_code' => $product->product_code,
                        'quantity' => $item['qty'],
                        'unit_price' => $unit_price,
                        'sub_total' => $item['price'] * $item['qty'],
                        'product_discount_amount' => 0,
                        'product_discount_type' => 'percent',
                        'product_tax_amount' => $tax,
                    ]);
                }
            }
            // End Save Sale Details

            DB::commit();

            // Start PDF Generation
            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = "invoice_{$timestamp}.pdf";

            $pdf = Pdf::loadView('filament.resources.views.pdf.invoice', [
                'cart' => $this->cart,
                'subtotal' => $this->subtotal,
                'discountPercent' => $this->discount_percent,
                'discountAmount' => $this->discountValue,
                'total' => $this->total,
                'customerName' => $this->customer_name,
                'invoiceTime' => now(),
            ])->setPaper('A4');

            // RESET CART
            $this->clearAll();

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $fileName);
            // End PDF Generation

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    // End Save Sales + Sale Details + Generate PDF
}
