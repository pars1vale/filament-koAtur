<?php

namespace App\Filament\Resources\Sales\SaleReturnResource\Pages;

use App\Filament\Resources\Sales\SaleReturnResource;
use App\Models\Sales\SaleReturnPayment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditSaleReturn extends EditRecord
{
    protected static string $resource = SaleReturnResource::class;
    protected static ?string $title = 'Ubah Retur Penjualan';

    protected function afterSave(): void
    {
        $saleReturn = $this->record;
        $outletId = Auth::user()->outlets->first()?->id;

        if ($saleReturn->wasChanged('paid_amount') && $saleReturn->paid_amount > 0) {
            SaleReturnPayment::create([
                'sale_return_id'    => $saleReturn->id,
                'outlet_id' => $outletId,
                'amount'         => $saleReturn->paid_amount,
                'date'           => now(),
                'reference'      => 'PAY/' . $saleReturn->reference,
                'payment_method' => $saleReturn->payment_method,
                'note'           => $saleReturn->note,
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
