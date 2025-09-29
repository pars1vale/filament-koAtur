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
                ->live()
                ->getSearchResultsUsing(fn (string $query) =>
                    Product::query()
                        ->where('product_name', 'like', "%{$query}%")
                        ->orWhere('product_code', 'like', "%{$query}%")
                        ->limit(10)
                        ->pluck('product_name', 'id')
                )
                ->getOptionLabelUsing(fn ($value): ?string => Product::find($value)?->product_name),

                Forms\Components\TextInput::make('barcode_qty')
                    ->label('Number of Barcodes')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required(),
        ];
    }

    protected function getTableQuery()
    {
        return Product::query()
            ->when($this->product_id, fn ($q) => $q->where('id', $this->product_id));
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('product_name')->label('Product Name'),
            Tables\Columns\TextColumn::make('product_code')->label('Product Code'),
        ];
    }

    public function generateBarcode()
    {
        $product = Product::find($this->product_id);

        if (!$product) {
            return;
        }

        $pdf = Pdf::loadView('filament.resources.views.pdf.barcode', [
            'product' => $product,
            'qty' => $this->barcode_qty,
        ]);

        return Response::streamDownload(
            fn () => print($pdf->output()),
            'barcode-' . $product->product_code . '.pdf'
        );
    }
}
