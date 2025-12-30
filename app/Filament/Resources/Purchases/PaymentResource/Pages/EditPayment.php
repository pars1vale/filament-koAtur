<?php

namespace App\Filament\Resources\Purchases\PaymentResource\Pages;

use App\Filament\Resources\Purchases\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;
    protected static ?string $title = 'Ubah Pembayaran Pembelian';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
