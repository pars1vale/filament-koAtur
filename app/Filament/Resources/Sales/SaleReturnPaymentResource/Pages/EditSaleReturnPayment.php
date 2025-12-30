<?php

namespace App\Filament\Resources\Sales\SaleReturnPaymentResource\Pages;

use App\Filament\Resources\Sales\SaleReturnPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSaleReturnPayment extends EditRecord
{
    protected static string $resource = SaleReturnPaymentResource::class;
    protected static ?string $title = 'Ubah Pengembalian Pembayaran';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
