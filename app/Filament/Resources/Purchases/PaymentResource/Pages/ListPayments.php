<?php

namespace App\Filament\Resources\Purchases\PaymentResource\Pages;

use App\Filament\Resources\Purchases\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;
    protected static ?string $title = 'Pembayaran Pembelian';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
