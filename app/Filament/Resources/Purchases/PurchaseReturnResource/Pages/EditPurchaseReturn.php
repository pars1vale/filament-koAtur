<?php

namespace App\Filament\Resources\Purchases\PurchaseReturnResource\Pages;

use App\Filament\Resources\Purchases\PurchaseReturnResource;
use App\Models\Purchases\PurchaseReturnPayment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseReturn extends EditRecord
{
    protected static string $resource = PurchaseReturnResource::class;

    protected function afterSave(): void
    {
        $purchaseReturn = $this->record;

        $oldStatus = $purchaseReturn->getOriginal('status');
        $newStatus = $purchaseReturn->status;

        if ($oldStatus !== 'completed' && $newStatus === 'completed') {
            foreach ($purchaseReturn->product_details as $detail) {
                if ($detail->product) {
                    $detail->product->decrement('product_quantity', $detail->quantity);
                }
            }
        }

        if ($oldStatus === 'completed' && $newStatus !== 'completed') {
            foreach ($purchaseReturn->product_details as $detail) {
                if ($detail->product) {
                    $detail->product->increment('product_quantity', $detail->quantity);
                }
            }
        }

        // Payment pertama saja
        $payment = PurchaseReturnPayment::where('purchase_return_id', $purchaseReturn->id)->first();

        if ($purchaseReturn->paid_amount > 0) {
            if ($payment) {
                $payment->update([
                    'amount'         => $purchaseReturn->paid_amount,
                    'date'           => $purchaseReturn->date,
                    'reference'      => 'PYR/' . $purchaseReturn->reference,
                    'payment_method' => $purchaseReturn->payment_method,
                    'note'           => $purchaseReturn->note,
                ]);
            } else {
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



    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
