<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\Products\Product;
use App\Models\Adjustments\Adjustment;
use App\Models\Adjustments\AdjustedProduct;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdjustStock extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Create Adjustment';
    protected static ?string $navigationGroup = 'Stock Adjustments';
    protected static string $view = 'filament.pages.adjustments.adjust-stock';

    public $product_id;
    public $note;
    public $items = [];
    public $reference;
    public $adjustment_date;



    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('product_id')
                ->label('Search Product')
                ->searchable()
                ->getSearchResultsUsing(
                    fn(string $query) =>
                    Product::query()
                        ->where('product_name', 'like', "%{$query}%")
                        ->orWhere('product_code', 'like', "%{$query}%")
                        ->limit(10)
                        ->pluck('product_name', 'id')
                )
                ->getOptionLabelUsing(fn($value): ?string => Product::find($value)?->product_name)
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    $product = Product::find($state);
                    if ($product && !collect($this->items)->pluck('id')->contains($product->id)) {
                        $this->items[] = [
                            'id' => $product->id,
                            'name' => $product->product_name,
                            'code' => $product->product_code,
                            'stock' => $product->product_quantity,
                            'quantity' => 0,
                            'type' => 'add',
                        ];
                    }
                }),
                
            Forms\Components\Group::make([
                Forms\Components\TextInput::make('reference')
                    ->label('Reference Code')
                    ->disabled()
                    ->default(function () {
                        $lastAdjustment = Adjustment::orderBy('id', 'desc')->first();
                        $lastNumber = 0;

                        if ($lastAdjustment) {
                            $lastNumber = (int) str_replace('ADJ-', '', $lastAdjustment->reference);
                        }

                        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

                        return 'ADJ-' . $newNumber;
                    }),

                Forms\Components\DatePicker::make('adjustment_date')
                    ->label('Date')
                    ->default(now())
                    ->required()
                    ->native(false),
            ])
                ->columns(2),
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        if (empty($this->items)) {
            return;
        }

        $formState = $this->form->getState();

        $lastAdjustment = Adjustment::orderBy('id', 'desc')->first();
        $lastNumber = 0;

        if ($lastAdjustment) {
            $lastNumber = (int) str_replace('ADJ-', '', $lastAdjustment->reference);
        }

        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

        $outletId = Auth::user()->outlets()->first()?->id ?? 1;

        $adjustment = Adjustment::create([
            'outlet_id' => $outletId,
            'date' => $formState['adjustment_date'] ?? now(),
            'reference' => 'ADJ-' . $newNumber,
            'note' => $this->note,
        ]);

        foreach ($this->items as $item) {
            AdjustedProduct::create([
                'outlet_id' => $outletId,
                'adjustment_id' => $adjustment->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'type' => $item['type'],
            ]);

            $product = Product::find($item['id']);
            if ($product) {
                if ($item['type'] === 'add') {
                    $product->product_quantity += $item['quantity'];
                } else {
                    $product->product_quantity -= $item['quantity'];
                }
                $product->save();
            }
        }

        $this->items = [];
        $this->note = null;
        $this->form->fill();

        Notification::make()
            ->title('Stock adjustment created successfully')
            ->success()
            ->send();
    }

    public function mount(): void
    {
        $this->form->fill();
    }
}
