<?php

namespace App\Filament\Resources\Sales\SaleResource\Pages;

use App\Filament\Resources\Sales\SaleResource;
use App\Models\Sales\SalePayment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function afterCreate(): void
    {
        $sale = $this->record;

        // push ke table payment
        if ($sale->paid_amount > 0) {
            SalePayment::create([
                'sale_id'    => $sale->id,
                'amount'         => $sale->paid_amount,
                'date'           => $sale->date,
                'reference'      => 'PAY/' . $sale->reference,
                'payment_method' => $sale->payment_method,
                'note'           => $sale->note,
            ]);
        }
    }
}
