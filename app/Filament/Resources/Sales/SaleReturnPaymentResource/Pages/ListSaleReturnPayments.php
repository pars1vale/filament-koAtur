<?php

namespace App\Filament\Resources\Sales\SaleReturnPaymentResource\Pages;

use App\Filament\Resources\Sales\SaleReturnPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSaleReturnPayments extends ListRecords
{
    protected static string $resource = SaleReturnPaymentResource::class;
    protected static ?string $title = 'Pengembalian Pembayaran';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
