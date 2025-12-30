<?php

namespace App\Filament\Resources\Sales\SaleResource\Pages;

use App\Filament\Resources\Sales\SaleResource;
use App\Models\Sales\SalePayment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;
    protected static ?string $title = 'Buat Penjualan';

    protected function afterCreate(): void
    {
        $sale = $this->record;
        $outletId = Filament::getTenant()?->id;

        // push ke table payment
        if ($sale->paid_amount > 0) {
            SalePayment::create([
                'sale_id'        => $sale->id,
                'outlet_id'      => $outletId,
                'amount'         => $sale->paid_amount,
                'date'           => $sale->date,
                'reference'      => 'PAY/' . $sale->reference,
                'payment_method' => $sale->payment_method,
                'note'           => $sale->note,
            ]);
        }
    }
}
