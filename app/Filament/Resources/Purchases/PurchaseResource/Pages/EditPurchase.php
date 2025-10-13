<?php

namespace App\Filament\Resources\Purchases\PurchaseResource\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\Purchases\PurchasePayment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function afterSave(): void
    {
        $purchase = $this->record;
        $outletId = Auth::user()->outlets->first()?->id;

        if ($purchase->wasChanged('paid_amount') && $purchase->paid_amount > 0) {
            PurchasePayment::create([
                'purchase_id'    => $purchase->id,
                'outlet_id' => $outletId,
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
