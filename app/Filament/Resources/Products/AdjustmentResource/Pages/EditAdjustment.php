<?php

namespace App\Filament\Resources\Products\AdjustmentResource\Pages;

use App\Filament\Resources\Products\AdjustmentResource;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use App\Models\Products\Product;
use App\Models\Adjustments\Adjustment;
use App\Models\Adjustments\AdjustedProduct;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class EditAdjustment extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = AdjustmentResource::class;

    protected static string $view = 'filament.pages.adjustments.edit-stock';

    public $product_id;
    public Adjustment $record;
    public $items = [];
    public $note;
    public $reference;
    public $adjustment_date;

    public function mount(Adjustment $record): void
    {
        $this->record = $record;
        $this->reference = $record->reference;
        $this->adjustment_date = $record->date;
        $this->note = $record->note;

        $this->items = $record->products()
            ->with('product')
            ->get()
            ->map(fn($p) => [
                'id' => $p->product_id,
                'name' => $p->product->product_name,
                'code' => $p->product->product_code,
                'stock' => $p->product->product_quantity,
                'quantity' => $p->quantity,
                'type' => $p->type,
            ])
            ->toArray();

        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('product_id')
                ->label('Cari Produk')
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
                    ->label('Kode Referensi')
                    ->disabled()
                    ->default(fn() => $this->reference),

                Forms\Components\DatePicker::make('adjustment_date')
                    ->label('Tanggal')
                    ->default(fn() => $this->adjustment_date)
                    ->required()
                    ->native(false),
            ])->columns(2),
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function update()
    {
        if (empty($this->items)) {
            return;
        }

        DB::transaction(function () {
            $formState = $this->form->getState();

            $this->record->update([
                'date' => $formState['adjustment_date'],
                'note' => $this->note,
            ]);

            $oldItems = $this->record->products()->get();
            foreach ($oldItems as $old) {
                $product = Product::find($old->product_id);
                if ($product) {
                    if ($old->type === 'add') {
                        $product->product_quantity -= $old->quantity;
                    } elseif ($old->type === 'sub') {
                        $product->product_quantity += $old->quantity;
                    }
                    $product->save();
                }
            }

            $this->record->products()->delete();

            foreach ($this->items as $item) {
                AdjustedProduct::create([
                    'adjustment_id' => $this->record->id,
                    'outlet_id' => $this->record->outlet_id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'type' => $item['type'],
                ]);

                $product = Product::find($item['id']);
                if ($product) {
                    if ($item['type'] === 'add') {
                        $product->product_quantity += $item['quantity'];
                    } elseif ($item['type'] === 'sub') {
                        $product->product_quantity -= $item['quantity'];
                    }
                    $product->save();
                }
            }
        });

        Notification::make()
            ->title('Penyesuaian stok berhasil diperbarui')
            ->success()
            ->send();

        return redirect(static::getResource()::getUrl('index'));
    }
}
