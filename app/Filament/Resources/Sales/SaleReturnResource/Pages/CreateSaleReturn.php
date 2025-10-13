<?php

namespace App\Filament\Resources\Sales\SaleReturnResource\Pages;

use App\Filament\Resources\Sales\SaleReturnResource;
use App\Models\Sales\SaleReturnPayment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSaleReturn extends CreateRecord
{
    protected static string $resource = SaleReturnResource::class;

    protected function afterCreate(): void
    {
        $saleReturn = $this->record;
        $outletId = Auth::user()->outlets->first()?->id;

        // push ke table payment
        if ($saleReturn->paid_amount > 0) {
            SaleReturnPayment::create([
                'sale_return_id' => $saleReturn->id,
                'outlet_id'      => $outletId,
                'amount'         => $saleReturn->paid_amount,
                'date'           => $saleReturn->date,
                'reference'      => 'PYR/' . $saleReturn->reference,
                'payment_method' => $saleReturn->payment_method,
                'note'           => $saleReturn->note,
            ]);
        }
    }
}
