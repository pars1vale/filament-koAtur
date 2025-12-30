<?php

namespace App\Filament\Resources\Sales\SalePaymentResource\Pages;

use App\Filament\Resources\Sales\SalePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalePayments extends ListRecords
{
    protected static string $resource = SalePaymentResource::class;
    protected static ?string $title = 'Pembayaran Penjualan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
