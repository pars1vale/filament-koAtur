<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Products\Product;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Barryvdh\DomPDF\Facade\Pdf;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Illuminate\Support\Facades\Response;

class PrintBarcode extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable, HasPageShield;

    protected static string $view = 'filament.pages.print-barcode';

    protected static ?string $navigationLabel = 'Print Barcode';
    protected static ?string $navigationGroup = 'Products';
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?int $navigationSort = 3;

    public ?int $product_id = null;
    public ?int $barcode_qty = 1;
    public array $items = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('product_id')
                ->label('Search Product')
                ->searchable()
                ->getSearchResultsUsing(fn (string $query) =>
                    Product::query()
                        ->where('product_name', 'like', "%{$query}%")
                        ->orWhere('product_code', 'like', "%{$query}%")
                        ->limit(10)
                        ->pluck('product_name', 'id')
                )
                ->getOptionLabelUsing(fn ($value): ?string => Product::find($value)?->product_name)
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    $product = Product::find($state);

                    if ($product && !collect($this->items)->pluck('id')->contains($product->id)) {
                        $this->items[] = [
                            'id' => $product->id,
                            'name' => $product->product_name,
                            'code' => $product->product_code,
                            'quantity' => 1,
                        ];
                    }
                }),
        ];
    }

    protected function getTableQuery()
    {
        return Product::query()->whereIn('id', collect($this->items)->pluck('id'));
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('product_name')->label('Product Name'),
            Tables\Columns\TextColumn::make('product_code')->label('Product Code'),
        ];
    }

    public function removeItem($id): void
    {
        $this->items = array_values(array_filter($this->items, fn ($item) => $item['id'] !== $id));
    }

    public function generateBarcode()
    {
        $products = [];

        foreach ($this->items as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'barcode_qty' => $item['quantity'],
                ];
            }
        }

        if (empty($products)) {
            return;
        }

        $pdf = Pdf::loadView('filament.resources.views.pdf.barcode', [
            'products' => $products,
        ]);

        $filename = 'barcode_' . now()->format('dmYHis') . '.pdf';
        
        return Response::streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }
}
