<?php

namespace App\Filament\Resources\Sales\SaleReturnResource\Pages;

use App\Filament\Resources\Sales\SaleReturnResource;
use App\Models\Sales\SaleReturnPayment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSaleReturn extends EditRecord
{
    protected static string $resource = SaleReturnResource::class;

    protected function afterSave(): void
    {
        $saleReturn = $this->record;

        $oldStatus = $saleReturn->getOriginal('status');
        $newStatus = $saleReturn->status;

        if ($oldStatus !== 'completed' && $newStatus === 'completed') {
            foreach ($saleReturn->product_details as $detail) {
                if ($detail->product) {
                    $detail->product->decrement('product_quantity', $detail->quantity);
                }
            }
        }

        if ($oldStatus === 'completed' && $newStatus !== 'completed') {
            foreach ($saleReturn->product_details as $detail) {
                if ($detail->product) {
                    $detail->product->increment('product_quantity', $detail->quantity);
                }
            }
        }

        // Payment pertama saja
        $payment = SaleReturnPayment::where('sale_return_id', $saleReturn->id)->first();

        if ($saleReturn->paid_amount > 0) {
            if ($payment) {
                $payment->update([
                    'amount'         => $saleReturn->paid_amount,
                    'date'           => $saleReturn->date,
                    'reference'      => 'PYR/' . $saleReturn->reference,
                    'payment_method' => $saleReturn->payment_method,
                    'note'           => $saleReturn->note,
                ]);
            } else {
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
