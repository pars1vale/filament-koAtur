<?php

namespace App\Filament\Resources\Sales\SaleReturnResource\Pages;

use App\Filament\Resources\Sales\SaleReturnResource;
use App\Models\Sales\SaleReturnPayment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSaleReturn extends CreateRecord
{
    protected static string $resource = SaleReturnResource::class;

    protected function afterCreate(): void
    {
        $saleReturn = $this->record;

        if ($saleReturn->status === 'completed') {
            foreach ($saleReturn->product_details as $detail) {
                if ($detail->product) {
                    $detail->product->decrement('product_quantity', $detail->quantity);
                }
            }
        }

        if ($saleReturn->paid_amount > 0) {
            SaleReturnPayment::create([
                'sale_return_id' => $saleReturn->id,
                'amount'             => $saleReturn->paid_amount,
                'date'               => $saleReturn->date,
                'reference'          => 'PYR/' . $saleReturn->reference,
                'payment_method'     => $saleReturn->payment_method,
                'note'               => $saleReturn->note,
            ]);
        }
    }
}
