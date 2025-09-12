<?php

namespace App\Filament\Resources\Purchases\PurchaseResource\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\Purchases\PurchasePayment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function afterCreate(): void
    {
        $purchase = $this->record;

        // push ke table payment
        if ($purchase->paid_amount > 0) {
            PurchasePayment::create([
                'purchase_id'    => $purchase->id,
                'amount'         => $purchase->paid_amount,
                'date'           => $purchase->date,
                'reference'      => 'PAY/' . $purchase->reference,
                'payment_method' => $purchase->payment_method,
                'note'           => $purchase->note,
            ]);
        }
    }
}
