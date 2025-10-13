<?php

namespace App\Filament\Resources\Sales\SaleResource\Pages;

use App\Filament\Resources\Sales\SaleResource;
use App\Models\Sales\SalePayment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Facades\Filament;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    protected function afterSave(): void
    {
        $sale = $this->record;
        $outletId = Filament::getTenant()?->id;

        if ($sale->wasChanged('paid_amount') && $sale->paid_amount > 0) {
            SalePayment::create([
                'sale_id'        => $sale->id,
                'outlet_id'      => $outletId,
                'amount'         => $sale->paid_amount,
                'date'           => now(),
                'reference'      => 'PAY/' . $sale->reference,
                'payment_method' => $sale->payment_method,
                'note'           => $sale->note,
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
