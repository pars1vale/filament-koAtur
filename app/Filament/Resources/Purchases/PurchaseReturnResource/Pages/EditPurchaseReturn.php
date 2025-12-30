<?php

namespace App\Filament\Resources\Purchases\PurchaseReturnResource\Pages;

use App\Filament\Resources\Purchases\PurchaseReturnResource;
use App\Models\Purchases\PurchaseReturnPayment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPurchaseReturn extends EditRecord
{
    protected static string $resource = PurchaseReturnResource::class;
    protected static ?string $title = 'Ubah Retur Pembelian';

    protected function afterSave(): void
    {
        $purchaseReturn = $this->record;
        $outletId = Auth::user()->outlets->first()?->id;

        if ($purchaseReturn->wasChanged('paid_amount') && $purchaseReturn->paid_amount > 0) {
            PurchaseReturnPayment::create([
                'purchase_id'    => $purchaseReturn->id,
                'outlet_id' => $outletId,
                'amount'         => $purchaseReturn->paid_amount,
                'date'           => now(),
                'reference'      => 'PAY/' . $purchaseReturn->reference,
                'payment_method' => $purchaseReturn->payment_method,
                'note'           => $purchaseReturn->note,
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
