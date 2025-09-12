<?php

namespace App\Filament\Resources\Purchases\PurchaseReturnResource\Pages;

use App\Filament\Resources\Purchases\PurchaseReturnResource;
use App\Models\Purchases\PurchaseReturnPayment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseReturn extends CreateRecord
{
    protected static string $resource = PurchaseReturnResource::class;

    protected function afterCreate(): void
    {
        $purchaseReturn = $this->record;

        if ($purchaseReturn->status === 'completed') {
            foreach ($purchaseReturn->product_details as $detail) {
                if ($detail->product) {
                    $detail->product->decrement('product_quantity', $detail->quantity);
                }
            }
        }

        if ($purchaseReturn->paid_amount > 0) {
            PurchaseReturnPayment::create([
                'purchase_return_id' => $purchaseReturn->id,
                'amount'             => $purchaseReturn->paid_amount,
                'date'               => $purchaseReturn->date,
                'reference'          => 'PYR/' . $purchaseReturn->reference,
                'payment_method'     => $purchaseReturn->payment_method,
                'note'               => $purchaseReturn->note,
            ]);
        }
    }
}
