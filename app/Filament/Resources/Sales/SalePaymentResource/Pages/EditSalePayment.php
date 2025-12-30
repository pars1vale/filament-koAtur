<?php

namespace App\Filament\Resources\Sales\SalePaymentResource\Pages;

use App\Filament\Resources\Sales\SalePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalePayment extends EditRecord
{
    protected static string $resource = SalePaymentResource::class;
    protected static ?string $title = 'Ubah Pembayaran Penjualan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
