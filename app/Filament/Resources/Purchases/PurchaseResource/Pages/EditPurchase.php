<?php

namespace App\Filament\Resources\Purchases\PurchaseResource\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\Purchases\PurchasePayment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function afterSave(): void
    {
        $purchase = $this->record;
        
        if ($purchase->wasChanged('paid_amount') && $purchase->paid_amount > 0) {
            PurchasePayment::create([
                'purchase_id'    => $purchase->id,
                'amount'         => $purchase->paid_amount,
                'date'           => now(),
                'reference'      => 'PAY/' . $purchase->reference,
                'payment_method' => $purchase->payment_method,
                'note'           => $purchase->note,
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
