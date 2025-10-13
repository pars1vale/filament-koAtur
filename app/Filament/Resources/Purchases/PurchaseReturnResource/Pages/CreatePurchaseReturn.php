<?php

namespace App\Filament\Resources\Purchases\PurchaseReturnResource\Pages;

use App\Filament\Resources\Purchases\PurchaseReturnResource;
use App\Models\Purchases\PurchaseReturnPayment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePurchaseReturn extends CreateRecord
{
    protected static string $resource = PurchaseReturnResource::class;

    protected function afterCreate(): void
    {
        $purchaseReturn = $this->record;
        $outletId = Auth::user()->outlets->first()?->id;


        if ($purchaseReturn->paid_amount > 0) {
            PurchaseReturnPayment::create([
                'purchase_return_id' => $purchaseReturn->id,
                'outlet_id' => $outletId,
                'amount'             => $purchaseReturn->paid_amount,
                'date'               => $purchaseReturn->date,
                'reference'          => 'PYR/' . $purchaseReturn->reference,
                'payment_method'     => $purchaseReturn->payment_method,
                'note'               => $purchaseReturn->note,
            ]);
        }
    }
}
